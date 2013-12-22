<?php
/**
 * Created by Anton Logvinenko.
 * email: a.logvinenko@mobidev.biz
 * Date: 5/13/13
 * Time: 12:46 PM
 */

class Notification extends BaseAppComponent
{
    /**
     * object that implements DB operations
     * @var NotificationDb
     */
    protected $_db;

    /**
     * component that implements queue list for notifications
     * @var QueueStorage
     */
    protected $_queueStorage;

    /**
     * object class that implements queue for notifications
     * @var string
     */
    public $queueClass = '';

    /**
     * component that implements queue for notifications
     * @var string
     */
    public $queueStorageComponent = '';

    /**
     * holds the number of e-mails sent a time from console command
     * @var int
     */
    public $numberOfEmailsSentAtATime = 100;

    /**
     * holds the number of e-mails in one pop command
     * @var int
     */
    public $numberOfEmailsInPortion = 10;

    /**
     * holds the sender's email address of e-mails
     * @var string
     */
    public $emailFrom = '';

    const USER_TYPE_CUSTOMER = 1;
    const USER_TYPE_EMPLOYEE = 2;

    const MESSAGE_TYPE_EMAIL = 1;
    const MESSAGE_TYPE_SMS = 2;

    public function init()
    {
        parent::init();

        $class = get_called_class();
        $component = strtolower($class);
        Yii::import($this->componentsPathAllias . '.' . $component . '.queueStorage.*');
        if ($this->queueStorageComponent) {
            $this->_queueStorage = new $this->queueClass($this->queueStorageComponent);
        } else {
            throw new Exception($class . ".queueStorageComponent is not defined");
        }
    }

    /**
     * @param $id
     * @return NotificationContainer
     */
    public function getById($id)
    {
        return $this->_db->findNotificationById($id);
    }

    /**
     * @param $IDs
     * @return array
     */
    public function getAllByIds($IDs)
    {
        return $this->_db->findAllNotificationsByIds($IDs);
    }

    /**
     * @param NotificationContainer $container
     * @return NotificationContainer
     */
    public function create(NotificationContainer $container)
    {
        return $this->_db->create($container);
    }

    /**
     * @param NotificationContainer $container
     * @return NotificationContainer
     */
    public function update(NotificationContainer $container)
    {
        return $this->_db->update($container);
    }

    /**
     * @param $id
     * @return bool
     * @throws Exception
     */
    public function deleteById($id)
    {
        $notification = $this->getById($id);
        if (!isset($notification->id)) {
            throw new Exception('Notification with id ' . $id . ' not found');
        }
        if ($this->_db->deleteById($notification->id) == true) {
            return true;
        }
    }

    public function createEmail(EmailContainer $container)
    {
        if (!$container->emailFrom) {
            $container->emailFrom = $this->emailFrom;
        }
        if (empty($container->emailTo)) {
            return false;
        }

        return $this->_queueStorage->pushElementToQueue(CacheKey::messageQueue(), $container);
    }

    public function getEmail()
    {
        return $this->_queueStorage->popElementFromQueue(CacheKey::messageQueue());
    }

    public function getEmailsPortion($numberOfEmails = NULL)
    {
        if(!(is_int($numberOfEmails)) || ($numberOfEmails <= 0)) {
            $numberOfEmails = $this->numberOfEmailsInPortion;
        }
        return $this->_queueStorage->popElementsFromQueue(CacheKey::messageQueue(), $numberOfEmails);
    }

    /**
     * @param EmailContainer $container
     * @return bool
     */
    public function sendEmail(EmailContainer $container)
    {
        $message = new YiiMailMessage;
        if ($container->typeHtml) {
            $message->setBody($container->text, 'text/html');
        } else {
            $message->setBody($container->text, 'text/plain');
        }
        $message->subject = $container->subject;
        if( is_array($container->emailTo) ){
            foreach( $container->emailTo as $recipient ){
                $message->addTo($recipient, $recipient);
            }
        } else {
            $message->addTo($container->emailTo, $container->nameTo);
        }
        $message->setFrom($container->emailFrom, $container->nameFrom);
        if(is_array($container->attachements) ){
            foreach( $container->attachements as $attachement ){
                $message->attach($attachement);
            }
        }
        try {
            $emailSettings = Yii::app()->emailSettings->getDefaultEmailSettings();
            Yii::app()->mail->transportOptions = array(
                'host' => $emailSettings->host,
                'username' => $emailSettings->username,
                'port' => $emailSettings->port,
                'password' => $emailSettings->password,
                'encryption' => Yii::app()->emailSettings->getEncryptionNameById( $emailSettings->encryptionId ),
            );
            if (Yii::app()->mail->send($message)) {
                return true;
            } else {
                $this->createEmail($container);
            }
        } catch (Exception $e) {
            $this->createEmail($container);
        }
        return false;
    }

    /**
     * @param array $containers
     * @return bool
     */
    public function sendEmails(array $containers) {
        if (!is_array($containers)) {
            return false;
        }
        $allEmailsSent = true;
        foreach ($containers as $container) {
            if ($container instanceof EmailContainer) {
                if (!$this->sendEmail( $container )) {
                    $allEmailsSent = false;
                }
            } else {
                return false;
            }
        }
        return $allEmailsSent;
    }

    /**
     * @param $result
     * @param $content
     * @param null $companyName
     * @param null $companyUrl
     * @param null $companyLogo
     * @return string
     */
    public function replaceValues( $result, $content, $companyName, $companyUrl = null, $companyLogo = null )
    {
        if( $companyName ){
            $modifiedCompanyName = array('{{companyName}}' => $companyName);
            $result = strtr( $result, $modifiedCompanyName );
        } else {
            $modifiedCompanyName = array('{{companyName}}' => '');
            $result = strtr( $result, $modifiedCompanyName );
        }
        if( $companyUrl ){
            $modifiedCompanyUrl = array('{{companyUrl}}' => '<address>
            <p>&copy; 2013 <a href=' . $companyUrl . ' target="_blank" style="color: #000;">' . $companyName . '</a><br>
            </address>');
            $result = strtr( $result, $modifiedCompanyUrl );
        } else {
            $modifiedCompanyUrl = array('{{companyUrl}}' => '');
            $result = strtr( $result, $modifiedCompanyUrl );
        }
        if( $companyLogo ){
            $imageContainer = Yii::app()->storage->getFileByUid( $companyLogo );
            if( $imageContainer ){
                $image = Yii::app()->storage->getFileUrlFromContainer($imageContainer);
                $modifiedCompanyImage = array('{{companyLogo}}' => '<center><img style="margin:0; padding: 0;" src="' . $image . '" alt="image" /></center>');
                $result = strtr( $result, $modifiedCompanyImage );
            }
        } else {
            $modifiedCompanyImage = array('{{companyImage}}' => '');
            $result = strtr( $result, $modifiedCompanyImage );
        }
        $modifiedContent = array('{{content}}' => $content);
        $result = strtr($result, $modifiedContent);
        return $this->addHeadAndFooterToContent($result);
    }

    /**
     * @param $content
     * @return string
     */
    public function addHeadAndFooterToContent( $content )
    {
        return "<!DOCTYPE html>
        <html lang='en'>
        <head>
            <META http-equiv='Content-Type' content='text/html; charset=utf-8' />
            <title>Email</title>
        </head>
        <body style='margin: 0; padding: 0; border: 0;'>" . $content . "</body>
        </html>";
    }

    /**
     * @param $content
     * @return mixed
     */
    public function removeHeadAndFooterToContent( $content )
    {
        return Yii::app()->purifier->purifyHTML( $content );
    }
}