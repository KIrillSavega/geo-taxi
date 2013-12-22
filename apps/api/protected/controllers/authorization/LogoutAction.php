<?php

class LogoutAction extends BaseApiAction
{
    public function run()
    {
        Yii::app()->user->logout();
        $this->renderSuccess( array("success"=>true) );
    }
}
