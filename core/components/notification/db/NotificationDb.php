<?php

use core\components\notification\models as Models;

class NotificationDb extends BaseDbImplementation
{
    public $containerRules = array(
        'NotificationContainer' => array(
            'id' => array('skipOnUpdate' => true, 'skipOnInsert' => true),
            'userId' => array('dbKey' => 'user_id'),
            'userType' => array('dbKey' => 'user_type'),
            'text' => array('dbKey' => 'text'),
            'isSent' => array('dbKey' => 'is_sent'),
            'messageType' => array('dbKey' => 'message_type'),
        )
    );

    public function findNotificationById($id)
    {
        return $this->selectContainerByPk($id, 'NotificationContainer', new Models\Message());
    }

    public function findAllNotificationsByIds($id)
    {
        return $this->selectAllContainersByPk($id, 'NotificationContainer', new Models\Message());
    }

    public function create($container)
    {
        return $this->insertContainer($container, new Models\Message());
    }

    public function update($container)
    {
        return $this->updateContainer($container, new Models\Message());
    }

    public function updateAttribute($notificationId, $attribute, $value)
    {
        return $this->updateContainerAttributeById($notificationId, 'NotificationContainer', new Models\Message(), $attribute, $value);
    }

    public function deleteById($id)
    {
        if(Models\Message::model()->deleteByPk($id)){
            return true;
        }
    }

}