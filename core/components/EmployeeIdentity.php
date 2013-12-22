<?php


class EmployeeIdentity extends CUserIdentity
{
    private $_id;

    public function authenticate(){
        $employee = Yii::app()->employee->getEmployeeByCompanyEmail($this->username);
        if($employee===null){
            $this->errorCode=self::ERROR_USERNAME_INVALID;
        }
        else if($employee->password!==UserHelper::hashPassword($this->password)){
            $this->errorCode=self::ERROR_PASSWORD_INVALID;
        }
        else
        {
            $this->_id=$employee->id;
            $this->setState('__name', $employee->firstName.' '.$employee->lastName);
            $this->errorCode=self::ERROR_NONE;
        }
        return !$this->errorCode;
    }

    public function getId()
    {
        return $this->_id;
    }
}