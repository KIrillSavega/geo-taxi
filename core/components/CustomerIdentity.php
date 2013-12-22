<?php


class CustomerIdentity extends CUserIdentity
{
    private $_id;

    public function authenticate()
    {
        $customer = Yii::app()->customer->getCustomerByPrivateEmail($this->username);
        if ($customer === null) {
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        } else if ($customer->password !== UserHelper::hashPassword($this->password)) {
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        } else {
            $this->_id = $customer->id;
            $this->setState('__id', $customer->id);
            $this->setState('__name', $customer->firstName . ' ' . $customer->lastName);
            $this->errorCode = self::ERROR_NONE;
        }
        return !$this->errorCode;
    }

    public function getId()
    {
        return $this->getState('__id');
    }

    public function getName()
    {
        return $this->getState('__name');
    }
}