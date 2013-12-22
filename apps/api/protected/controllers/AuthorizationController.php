<?php

class AuthorizationController extends BaseApiController
{
    public function actions() 
    {
        return array(
            'login' => 'application.controllers.authorization.LoginAction',
            'logout' => 'application.controllers.authorization.LogoutAction',
        );
    }
}
