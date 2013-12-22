<?php

class AuthorizationController extends BaseApiController
{
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
                'actions'=>array('logout'),
            ),
        );
    }
    
    public function actions() 
    {
        return array(
            'login' => 'application.controllers.authorization.LoginAction',
            'logout' => 'application.controllers.authorization.LogoutAction',
        );
    }
}
