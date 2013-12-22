<?php

class ModelContainerConverter extends CComponent
{
    const EMPTY_SCENARIO = 'empty';
    const INSERT_SCENARIO = 'insert';
    const UPDATE_SCENARIO = 'update';
    public $containerRules = array();
    public $containerClassName = '';

    /**
     *
     * @param array $containerRules
     * @param string $containerClassName
     */
    public function __construct($containerRules, $containerClassName)
    {
        $this->containerRules = $containerRules;
        $this->containerClassName = $containerClassName;
    }

    public static function containerSortOrder($containers, $orderBy, $sortOrder = SORT_DESC)
    {
        $sortArray = array();
        if (!empty($containers)) {
            foreach ($containers as $container) {
                foreach ($container as $key => $value) {
                    if (!isset($sortArray[$key])) {
                        $sortArray[$key] = array();
                    }
                    $sortArray[$key][] = $value;
                }
            }
            array_multisort($sortArray[$orderBy], $sortOrder, $containers);
        }

        return $containers;
    }

    /**
     *
     * @param CModel $model
     * @return \containerClassName
     */
    public function convertModelToContainer(CModel $model)
    {
        $container = new $this->containerClassName;
        foreach ($container as $containerKey => $containerValue) {
            $this->setAttributeToContainerFromModel($container, $model, $containerKey);
        }
        return $container;
    }
    
    protected function setAttributeToContainerFromModel(&$container, $model, $containerKey)
    {
        $containerSettings = isset($this->containerRules[$containerKey]) ? $this->containerRules[$containerKey] : array();
        if (!$this->getSkipOnConvertToContainer($containerSettings)) {
            $modelKey = $this->getDbKey($containerKey, $containerSettings);
            $afterFindCallback = $this->getAfterFindCallback($containerSettings);
            $container->{$containerKey} = $afterFindCallback ? call_user_func($afterFindCallback, $model->{$modelKey}) : $model->{$modelKey};
        }
    }
    
    public function convertModelToContainerWithJsonAttr(CModel $model, $settings)
    {
        $jsonAttr = $settings['storeAsJsonAttr'];
        $exclude = isset($settings['exclude']) ? $settings['exclude'] : array();
        $jsonModelKey = $this->getDbKey($jsonAttr, $this->containerRules);
        $json = $model->$jsonModelKey;
        $decoded = CJSON::decode($json);
        $decoded = is_array($decoded) ? $decoded : array();
        $container = new $this->containerClassName;
        foreach ($container as $containerKey => $containerValue) {
            if( !in_array($containerKey, $exclude) ){
                if( isset($decoded[$containerKey]) ){
                    $container->$containerKey = $decoded[$containerKey];
                } else{
                     $container->$containerKey = null;
                }
            } 
        }
        foreach( $this->containerRules as $attr => $rule ){
            $this->setAttributeToContainerFromModel($container, $model, $attr);
        }
        return $container;
    }

    protected function getSkipOnConvertToContainer($attributeSettings)
    {
        return isset($attributeSettings['skipOnConvertToContainer']) ? $attributeSettings['skipOnConvertToContainer'] : false;
    }

    protected function getAfterFindCallback($attributeSettings)
    {
        return isset($attributeSettings['afterFindCallback']) ? $attributeSettings['afterFindCallback'] : false;
    }

    /**
     *
     * @param $array
     * @return \containerClassName
     */
    public function convertArrayToContainer($array)
    {
        $container = new $this->containerClassName;
        foreach ($container as $containerKey => $containerValue) {
            $containerSettings = isset($this->containerRules[$containerKey]) ? $this->containerRules[$containerKey] : array();
            if (!$this->getSkipOnConvertToContainer($containerSettings)) {
                $arrayKey = $this->getDbKey($containerKey, $containerSettings);
                $afterFindCallback = $this->getAfterFindCallback($containerSettings);
                if( isset($array[$arrayKey]) ){
                    $container->{$containerKey} = $afterFindCallback ? call_user_func($afterFindCallback, $array[$arrayKey]) : $array[$arrayKey];
                }
            }
        }

        return $container;
    }

    /**
     * @param $array
     * @return array
     */
    public function convertArrayWithModelAttributesToArrayWithContainerAttributes($array)
    {
        $container = new $this->containerClassName;
        $result = array();
        foreach ($container as $containerKey => $containerValue) {
            $containerSettings = isset($this->containerRules[$containerKey]) ? $this->containerRules[$containerKey] : array();
            if (!$this->getSkipOnConvertToContainer($containerSettings)) {
                $arrayKey = $this->getDbKey($containerKey, $containerSettings);
                if (isset($array[$arrayKey])) {
                    $result[$containerKey] = $array[$arrayKey];
                }
            }
        }

        return $result;
    }

    /**
     * @param array $array
     * @return array
     */
    public function convertArrayWithContainerAttributesToArrayWithModelAttributes($array)
    {
        $converted = array();
        foreach ($array as $key => $attribute) {
            $containerAttributeRule = (isset($this->containerRules[$key]))? $this->containerRules[$key]:null;
            $modelKey = $this->getDbKey($key, $containerAttributeRule);
            if($modelKey){
                $converted[$modelKey] = $array[$key];
            }
        }

        return $converted;
    }

    /**
     *
     * @param \containerClassName $container
     * @param string $scenario
     * @return array
     */
    public function convertContainerToModelAttributes($container, $scenario = self::EMPTY_SCENARIO)
    {
        $attributes = (array)$container;
        foreach ($attributes as $key => $attribute) {
            if (isset($this->containerRules[$key])) {
                $containerSettings = $this->containerRules[$key];
                if ($this->getShouldSkipAttribute($containerSettings, $scenario)) {
                    unset($attributes[$key]);
                } else {
                    $modelKey = $this->getDbKey($key, $containerSettings);
                    $attributes[$modelKey] = $container->{$key};
                    if($scenario == self::INSERT_SCENARIO || $scenario == self::UPDATE_SCENARIO){
                        $purifyRule = $this->getPurifyRule($containerSettings);
                        if (!empty($purifyRule)) {
                            $attributes[$modelKey] = $this->purifyAttribute($attribute, $purifyRule);
                        }
                    }
                    if ($modelKey != $key) {
                        unset($attributes[$key]);
                    }
                }
            }
        }

        return $attributes;
    }
    
    public function convertContainerToModelAttributesWithJsonAttr($container, $scenario, $settings)
    {
        $jsonAttr = $settings['storeAsJsonAttr'];
        $exclude = isset($settings['exclude']) ? $settings['exclude'] : array();
        $attrs = get_object_vars($container);
        foreach( $exclude as $attrToExclude ){
            unset($attrs[$attrToExclude]);
        }
        $json = CJavaScript::encode($attrs);
        $container->$jsonAttr = $json;
        foreach( $attrs as $attr => $value ){
            if(property_exists($container, $attr) ){
                unset($container->$attr);
            }
        }
        return $this->convertContainerToModelAttributes($container, $scenario);
    }

    protected function getShouldSkipAttribute($attributeSettings, $scenario = self::EMPTY_SCENARIO)
    {
        switch ($scenario) {
            case self::INSERT_SCENARIO:
                return $this->getSkipOnInsert($attributeSettings);
                break;
            case self::UPDATE_SCENARIO:
                return $this->getSkipOnUpdate($attributeSettings);
                break;
            default:
                return false;
                break;
        }
    }

    protected function getSkipOnInsert($attributeSettings)
    {
        return isset($attributeSettings['skipOnInsert']) ? $attributeSettings['skipOnInsert'] : false;
    }

    protected function getSkipOnUpdate($attributeSettings)
    {
        return isset($attributeSettings['skipOnUpdate']) ? $attributeSettings['skipOnUpdate'] : false;
    }

    protected function getDbKey($attribute, $attributeSettings)
    {
        return isset($attributeSettings['dbKey']) ? $attributeSettings['dbKey'] : $attribute;
    }

    protected function getPurifyRule($attributeSettings)
    {
        return isset($attributeSettings['purify']) ? $attributeSettings['purify'] : false;
    }

    public function purifyAttribute($attribute, $rule)
    {
        return Yii::app()->purifier->{$rule}($attribute);
    }

    public function setContainerAttributeToModel(&$model, $containerAttribute, $containerValue)
    {
        $modelKey = $this->getDbKey($containerAttribute, $this->containerRules[$containerAttribute]);
        return $model->setAttribute($modelKey, $containerValue);
    }

    /**
     *
     * @param array $modelErrors
     * @return array
     */
    public function convertModelErrorsToContainerAttributes($modelErrors)
    {
        $container = new $this->containerClassName;
        $containerArray = (array)$container;
        $containerErrors = array();
        foreach ($modelErrors as $key => $error) {
            if (array_key_exists($key, $this->containerRules)) {
                $containerErrors[$key] = $error;
            } else {
                foreach ($containerArray as $attribute => $containerValue) {
                    $attributeSettings = (isset($this->containerRules[$attribute])) ? $this->containerRules[$attribute] : array();
                    $dbKey = $this->getDbKey($attribute, $attributeSettings);
                    if ($dbKey == $key) {
                        $containerErrors[$attribute] = $error;
                        break;
                    }
                }
            }
        }

        return $containerErrors;
    }

    public function convertAttributesNamesToModelAttributesNames($attributes)
    {
        $converted = array();
        foreach ($attributes as $key => $attribute) {
            $containerAttributeRule = (isset($this->containerRules[$attribute]))? $this->containerRules[$attribute]:null;
            $modelKey = $this->getDbKey($attribute, $containerAttributeRule);
            if($modelKey){
                $converted[] = $modelKey;
            }
        }

        return $converted;
    }
}