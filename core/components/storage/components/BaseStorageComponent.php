<?php

class BaseStorageComponent extends CComponent
{
    /**
     * object that implements DB operations
     * @var StorageDb
     */
    protected $_db;
    /**
     * object that implements Cache operations
     * @var StorageCache
     */
    protected $_cache;

    public $errors = array();

    /**
     * @var Storage
     */
    protected $storage;

    public function setDb($db)
    {
        $this->_db = $db;
    }

    public function setCache($cache)
    {
        $this->_cache = $cache;
    }

    public function __construct($component)
    {
        $this->storage = $component;
    }

    public function getErrors()
    {
        $dbErrors = $this->_db->getErrors();
        $this->errors = CMap::mergeArray($this->errors,  $dbErrors );

        return $this->errors;
    }

    /**
     * This function clears validators errors
     */
    public function clearErrors()
    {
        $this->_db->clearErrors();
        $this->errors = array();
    }
}