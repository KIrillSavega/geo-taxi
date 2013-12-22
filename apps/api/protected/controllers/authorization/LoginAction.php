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

    public function run($username, $password)
    {
        if (Yii::app()->user->isGuest) {
            $identity = new CustomerIdentity($username, $password);
            if ($identity->authenticate()) {
                Yii::app()->user->login($identity);
                $this->renderResponse();
            }
        } else {
            $this->renderResponse();
        }
    }

    protected function renderResponse()
    {
        $session = Yii::app()->apiSession->getSession();
        $this->renderSuccess(array(
            'sessionId' => $session->id
        ));
    }
}