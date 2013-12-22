<?php

class AuthorizationController extends BaseApiController
{
    public function init()
    {
        parent::init();
        Yii::import('application.controllers.customer.*');
    }

    public function filters()
    {
        return array(
            'accessControl',
        );
    }

    public function accessRules()
    {
        return array(
            array('deny',
                'users'=>array('?'),
                'actions'=>array('logout', 'registration'),
            ),
        );
    }

    public function actions() 
    {
        return array(
            'login' => 'application.controllers.authorization.LoginAction',
            'logout' => 'application.controllers.authorization.LogoutAction',
            'registration' => 'application.controllers.authorization.RegistrationAction',
        );
    }
}
