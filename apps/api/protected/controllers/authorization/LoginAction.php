<?php

class LoginAction extends BaseApiAction
{
    public function rules()
    {
        return array(
            array('username', 'required'),
            array('password', 'required'),
        );
    }
    
    public function run( $pinCode, $posTerminalId )
    {
        if(Yii::app()->user->isGuest){
            $identity = new EmployeeIdentity($pinCode, $posTerminalId);
            if( $identity->authenticate() ){
                Yii::app()->user->login($identity);
                $session = Yii::app()->apiSession();
                $this->renderSuccess(array(
                    'sessionId' => $session->id
                ));
            }
        }
    }
}