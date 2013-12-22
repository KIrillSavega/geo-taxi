<?php

class ApiRedisSession extends CApplicationComponent
{
    public $sessionLifeTime = 86400;
    public $webserviceSessionAttr = 'session_id';
    /**
     *
     * @var ApiSessionContainer
     */
    protected $session;

    public function open()
    {
        $sessionId = $this->getSessionId();
        $this->session = $this->getBySessionId($sessionId);
        return $this->session;
    }

    public function close()
    {
        if ($this->session instanceof ApiSessionContainer) {
            $this->deleteBySessionId($this->session->id);
            $this->session = null;
            Yii::app()->user->setId(null);
        }
    }

    protected function getBySessionId($sessionId)
    {
        return Yii::app()->redisPersistentStorage->get(CacheKey::apiSession($sessionId));
    }

    protected function deleteBySessionId($sessionId)
    {
        return Yii::app()->redisPersistentStorage->delete(CacheKey::apiSession($sessionId));
    }

    public function create(ApiSessionContainer $container)
    {
        $sessionId = $this->generateSessionId();
        $container->id = $sessionId;
        Yii::app()->redisPersistentStorage->set(CacheKey::apiSession($sessionId), $container, $this->sessionLifeTime);
        $this->session = $container;
        return $container;
    }

    public function renew()
    {
        if ($this->session instanceof ApiSessionContainer) {
            Yii::app()->redisPersistentStorage->set(CacheKey::apiSession($this->session->id), $this->session, $this->sessionLifeTime);
            return $this->session;
        }
    }

    public function getSession()
    {
        return $this->session;
    }

    protected function generateSessionId()
    {
        return md5(uniqid());
    }

    protected function getSessionId()
    {
        return isset($_GET[$this->webserviceSessionAttr]) ? $_GET[$this->webserviceSessionAttr] : null;
    }
}
