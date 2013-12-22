<?php
/**
 * Created by Anton Logvinenko.
 * Date: 3/11/13
 * Time: 6:26 PM
 */

class BaseAppComponent extends CApplicationComponent
{
    /**
     * Implementation class for interaction with DB
     * @var string
     */
    public $dbClass = '';
    /**
     * Implementation class for interaction with Cache
     * @var string
     */
    public $cacheClass = '';
    /**
     * Name of cache component, that should be use in component
     * @var string
     */
    public $cacheComponent = 'redisCache';

    /**
     * Change the selected database for the redis connection.
     * @var integer
     */
    public $redisDatabase = 0;
    /**
     *
     * @var string 
     */
    public $componentsPathAllias = 'core.components';
    /**
     * object that imlements DB operations
     * @var object
     */
    protected $_db = null;
    /**
     * object that imlements Cache operations
     * @var object
     */
    protected $_cache = null;
    /**
     * Component errors
     * @var array
     */
    public $errors = array();
    

    public function init()
    {
        $class = get_called_class();
        $reflection = new ReflectionClass($class);
        $path = $reflection->getFileName();
        $exploded = explode(DIRECTORY_SEPARATOR, $path);
        array_pop($exploded);
        $component = end($exploded);
        Yii::import($this->componentsPathAllias.'.' . $component . '.*');
        Yii::import($this->componentsPathAllias.'.' . $component . '.cache.*');
        Yii::import($this->componentsPathAllias.'.' . $component . '.db.*');
        Yii::import($this->componentsPathAllias.'.' . $component . '.models.*');
        Yii::import($this->componentsPathAllias.'.' . $component . '.models.gii.*');
        Yii::import($this->componentsPathAllias.'.' . $component . '.events.*');
        $this->attachEvents();
        parent::init();

        if ($this->dbClass) {
            $this->_db = new $this->dbClass();
        } else {
            throw new Exception($class . ".dbClass is not defined");
        }

        if ($this->cacheClass) {
            $this->_cache = new $this->cacheClass(Yii::app()->{$this->cacheComponent});
        }
    }

    /**
     * return object that imlements DB operations
     * @object CComponent
     */
    public function getDb()
    {
        return $this->_db;
    }

    /**
     * return object that imlements Cache operations
     * @object CComponent
     */
    public function getCache()
    {
        return $this->_cache;
    }

    /**
     *
     * @return array of database and component errors
     */
    public function getErrors()
    {
        return CMap::mergeArray($this->_db->getErrors(), $this->errors);
    }

    /**
     * This function clears validators errors
     */
    public function clearErrors()
    {
        $this->_db->clearErrors();
        $this->errors = array();
    }

    /**
     * @param array $errors
     */
    public function addErrors($errors, $isGeneral = true)
    {
        if ($isGeneral) {
            foreach ($errors as $error) {
                $this->errors['general'][] = $error;
            }
        } else {
            $this->errors = CMap::mergeArray($this->errors, $errors);
        }
    }

    /**
     * @return array
     */
    public function getGeneralErrors()
    {
        $result = array();
        if (isset($this->errors['general'])) {
            $result = $this->errors['general'];
        }

        return $result;
    }

    /**
     * @return string|null
     */
    public function getFirstGeneralError()
    {
        $errors = $this->getGeneralErrors();
        if ($errors) {
            return $errors[0];
        }
    }

    public function attachEvents()
    {
    }

    protected function baseGetById($id, $params)
    {
        if($id){
            $cacheGetter = isset($params['cacheGetter']) ? $params['cacheGetter'] : 'getById';
            $cacheSetter = isset($params['cacheSetter']) ? $params['cacheSetter'] : 'set';
            $dbFinderById = isset($params['dbFinderById']) ? $params['dbFinderById'] : 'findById';

            if($cacheGetter !== false){
                $container = $this->_cache->{$cacheGetter}($id);
            }else{
                $container = null;
            }
            if (!$container) {
                $container = $this->_db->{$dbFinderById}($id);
                if($cacheSetter !== false){
                    $this->_cache->{$cacheSetter}($container);
                }
            }

            return $container;
        }
    }

    protected function baseGetAllByIds($IDs, $params)
    {
        $cacheGetter = isset($params['cacheGetter']) ? $params['cacheGetter'] : 'getById';
        $cacheSetterAll = isset($params['cacheSetterAll']) ? $params['cacheSetterAll'] : 'setAll';
        $dbFinderAllById = isset($params['dbFinderAllByIds']) ? $params['dbFinderAllByIds'] : 'findAllByIds';
        $primaryKey = isset($params['primaryKey']) ? $params['primaryKey'] : 'id';

        $resultContainers = array();
        $IDsNotFoundInCache = array();
        if($cacheGetter !== false){
            $containersFromCache = $this->_cache->{$cacheGetter}($IDs);
            if ($containersFromCache) {
                foreach ($containersFromCache as $key => $container) {
                    if ($container == false) {
                        $IDsNotFoundInCache[] = $IDs[$key];
                    } else {
                        $resultContainers[] = $container;
                    }
                }
            } else {
                $IDsNotFoundInCache = $IDs;
            }
        }else{
            $IDsNotFoundInCache = $IDs;
        }

        if (!empty($IDsNotFoundInCache)) {
            $containersFromDb = $this->_db->{$dbFinderAllById}($IDsNotFoundInCache);
            if (!empty($containersFromDb) && ($cacheSetterAll !== false)) {
                $this->_cache->{$cacheSetterAll}($containersFromDb);
            }
            $resultContainers = CMap::mergeArray($resultContainers, $containersFromDb);
            // sort merged results by provided $IDs order
            $sortedByIdsResult = array();
            foreach ($IDs as $id) {
                $filtered = array_filter($resultContainers, function ($input) use ($id, $primaryKey) {
                    return $input->{$primaryKey} == $id;
                });
                if (!empty($filtered)) {
                    $sortedByIdsResult[] = array_pop($filtered);
                }
            }
            return $sortedByIdsResult;
        } else {
            // in this case containers already sorted by provided $IDs order
            return $resultContainers;
        }
    }

    protected function baseGetAllByCompositeKeys($IDs, $params)
    {
        $cacheGetter = isset($params['cacheGetter']) ? $params['cacheGetter'] : 'getById';
        $cacheSetterAll = isset($params['cacheSetterAll']) ? $params['cacheSetterAll'] : 'setAll';
        $dbFinderAllById = isset($params['dbFinderAllByIds']) ? $params['dbFinderAllByIds'] : 'findAllByIds';
        $primaryKey = isset($params['primaryKey']) ? $params['primaryKey'] : 'id';
        if (!is_array($primaryKey)) {
            $primaryKey = array($primaryKey);
        }

        $resultContainers = array();
        $IDsNotFoundInCache = array();
        if($cacheGetter !== false){
            $containersFromCache = $this->_cache->{$cacheGetter}($IDs);
            if ($containersFromCache) {
                foreach ($containersFromCache as $key => $container) {
                    if ($container == false) {
                        $IDsNotFoundInCache[] = $IDs[$key];
                    } else {
                        $resultContainers[] = $container;
                    }
                }
            } else {
                $IDsNotFoundInCache = $IDs;
            }
        }else{
            $IDsNotFoundInCache = $IDs;
        }

        if (!empty($IDsNotFoundInCache)) {
            $containersFromDb = $this->_db->{$dbFinderAllById}($IDsNotFoundInCache);
            if (!empty($containersFromDb) && ($cacheSetterAll !== false)) {
                $this->_cache->{$cacheSetterAll}($containersFromDb);
            }
            $resultContainers = CMap::mergeArray($resultContainers, $containersFromDb);
            // sort merged results by provided $IDs order
            $sortedByIdsResult = array();

            $convertedIDs = array();
            if ($IDs) {
                $converter = $this->_db->getConverter(get_class($resultContainers[0]));
                foreach ($IDs as $id) {
                    $convertedIDs[] = $converter->convertArrayWithModelAttributesToArrayWithContainerAttributes($id);
                }
            }

            foreach ($convertedIDs as $id) {
                $filtered = array_filter($resultContainers, function ($input) use ($id, $primaryKey) {
                    $diff = array_diff_assoc($id, (array) $input);
                    return empty($diff);
                });
                if (!empty($filtered)) {
                    $sortedByIdsResult[] = array_pop($filtered);
                }
            }
            return $sortedByIdsResult;
        } else {
            // in this case containers already sorted by provided $IDs order
            return $resultContainers;
        }
    }

    public function searchByContainerCriteria($container, $params = array())
    {
        $IDs = $this->_db->searchIDsByContainerCriteria($container, $params);
        return $this->getAllByIds($IDs);
    }

    public function getTotalCountForContainer($container, $params = array())
    {
        return $this->_db->getTotalCountForContainer($container, $params);
    }
    
    public function containerToString( $container )
    {
        $forbiddenAttributeNames = array(
            'password'
        );
        $result = "";
        if(is_object($container) ){
            $reflection = new ReflectionClass($container);
            $name = $reflection->getName();
            $result .= "Object: $name. ";
            foreach( $reflection->getProperties() as $attribute ){
                if( $attribute->isPublic() && !$attribute->isStatic() && !in_array($attribute->getName(), $forbiddenAttributeNames) ){
                    $value = $attribute->getValue($container);
                    $value = $value ? $value : '---';
                    $result .= $attribute->getName().": ".$value."; ";
                }
            }
        }
        return $result;
    }
}