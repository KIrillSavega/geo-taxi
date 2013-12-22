<?php

class ApiWebUser extends CWebUser
{
    protected $_state = array();

    public function init()
    {
        CApplicationComponent::init();
        $this->clearStates();
        $session = Yii::app()->apiSession->open();
        if ($session instanceof ApiSessionContainer) {
            if (is_array($session->states)) {
                foreach ($session->states as $key => $value) {
                    $this->setState($key, $value);
                }
            }
            $this->setId($session->userId);
            $this->setState('appId', $session->appId);
            $this->setState('name', $session->name);
            if ($this->autoRenewCookie && $this->allowAutoLogin) {
                Yii::app()->apiSession->renew();
            }
        }
    }

    public function loginRequired()
    {
        $action = Yii::app()->controller->action;
        if ($action instanceof BaseApiAction) {
            $action->renderError($action::ERR_AUTH);
        } else {
            echo "403 Authorization required";
            Yii::app()->end();
        }
    }

    public function login($identity, $duration = 0)
    {
        $id = $identity->getId();
        $states = $identity->getPersistentStates();
        if ($this->beforeLogin($id, $states, false)) {
            $this->changeIdentity($id, $identity->getName(), $states);
            $this->afterLogin(false);
        }
        return !$this->getIsGuest();
    }

    protected function changeIdentity($id, $name, $states)
    {
        $session = new ApiSessionContainer();
        $session->appId = POS_APP_ID;
        $session->states = $states;
        $session->userId = $id;
        $session->name = $name;
        $session->posTerminalId = $states['posTerminalId'];
        $session = Yii::app()->apiSession->create($session);
        $this->setId($id);
        $this->setName($name);
        $this->loadIdentityStates($states);
    }

    public function logout()
    {
        if ($this->beforeLogout()) {
            Yii::app()->apiSession->close();
            $this->clearStates();
            $this->afterLogout();
        }
    }
}
