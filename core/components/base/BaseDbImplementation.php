<?php
class BaseDbImplementation extends CComponent
{
    /**
     * Each Key of array is name of Container attribute
     * possible settings:
     * - dbKey: String. Container and database may have different keys for attributes. you can redefine attribute in DB.
     * By default it is equal to attribute key.
     * - skipOnInsert : Bool. If it's true this field are not set in INSERT query (on create). By default false
     * - skipOnUpdate: Bool. If is true you can modify user record with this field by User.updateProfile() method,
     * if false you may edit this attributes only by special methods. By default true
     * - beforeSaveCallback: a valid callback string. This callback function will modify attribute before save
     *
     * @var array
     */
    public $containerRules = array();
    /**
     * Database errors
     * @var array
     */
    public $errors = array();
    protected $converters = array();
    /**
     * @var array
     */
    public $containerToModel = array();

    public function __construct()
    {
        $this->converters = array();
        $containerRules = $this->getContainerRules();
        foreach ($containerRules as $containerName => $attributes) {
            $this->converters[$containerName] = new ModelContainerConverter($attributes, $containerName);
        }
    }

    public function getContainerRules()
    {
        return $this->containerRules;
    }

    public function getContainersToModel()
    {
        return $this->containerToModel;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function clearErrors()
    {
        $this->errors = array();
    }

    protected function selectContainerByPk($pk, $containerClass, CActiveRecord $ar)
    {
        $record = $ar->findByPk($pk);
        if (is_object($record)) {
            return $this->getConverter($containerClass)->convertModelToContainer($record);
        } else {
            return null;
        }
    }

    protected function selectAllContainersByPk($pk, $containerClass, CActiveRecord $ar)
    {
        $records = $ar->findAllByPk(array_values($pk));
        if (is_array($records)) {
            $containers = array();
            foreach ($records as $record) {
                $containers[] = $this->getConverter($containerClass)->convertModelToContainer($record);
            }
            return $containers;
        }
    }

    public function insertContainer($container, CActiveRecord $ar)
    {
        $containerClass = get_class($container);
        $insertAttributes = $this->getConverter($containerClass)
            ->convertContainerToModelAttributes($container, ModelContainerConverter::INSERT_SCENARIO);
        $ar->setAttributes($insertAttributes, false);
        if ($ar->save()) {
            return $this->getConverter($containerClass)->convertModelToContainer($ar);
        } else {
            $this->addErrors($ar->getErrors(), $containerClass);
            return null;
        }
    }

    /**
     * @param $containerName
     * @return ModelContainerConverter
     */
    public function getConverter($containerName)
    {
        if (isset($this->converters[$containerName])) {
            return $this->converters[$containerName];
        } else {
            throw new Exception('Tried to get not registered converter ' . $containerName);
        }
    }

    public function addErrors($modelErrors, $containerName = NULL)
    {
        $containerErrors = $this->getConverter($containerName)->convertModelErrorsToContainerAttributes($modelErrors);
        $this->errors = CMap::mergeArray($this->errors, $containerErrors);
    }

    public function updateContainer($container, CActiveRecord $ar, $pk='id')
    {
        $containerClass = get_class($container);
        $pkWithModelAttributes = null;
        if (is_string($pk)) {
            if (isset($container->{$pk})) {
                $pkWithModelAttributes = $container->{$pk};
            }
        } elseif (is_array($pk)) {
            $pkWithContainerAttributes = array();
            foreach ($pk as $containerAttribute) {
                if (isset($container->{$containerAttribute})) {
                    $pkWithContainerAttributes[$containerAttribute] = $container->{$containerAttribute};
                }
            }
            $pkWithModelAttributes = $this->getConverter($containerClass)
                ->convertArrayWithContainerAttributesToArrayWithModelAttributes($pkWithContainerAttributes);
        } else {
            throw new CException($containerClass. ': specified PK type is not supported');
        }

        if (!empty($pkWithModelAttributes)) {
            $model = $ar->findByPk($pkWithModelAttributes);
            if (!$model) {
                return null;
            } else {
                $updateAttributes = $this->getConverter($containerClass)
                    ->convertContainerToModelAttributes($container, ModelContainerConverter::UPDATE_SCENARIO);

                $model->setAttributes($updateAttributes, false);

                if ($model->save()) {
                    return $this->getConverter($containerClass)->convertModelToContainer($model);
                } else {
                    $this->addErrors($model->getErrors(), $containerClass);
                    return null;
                }
            }
        } else {
            throw new CException($containerClass . '.PK was not set before update');
        }
    }

    protected function updateContainerAttributeById($id, $containerClass, CActiveRecord $ar, $attribute, $value)
    {
        $record = $ar->findByPk($id);
        if ($record) {
            $this->getConverter($containerClass)->setContainerAttributeToModel($record, $attribute, $value);
            if ($record->save()) {
                return $this->getConverter($containerClass)->convertModelToContainer($record);
            } else {
                $this->addErrors($record->getErrors(), $containerClass);
                return null;
            }
        }
    }

    protected function getModelRelatedToContainer($container)
    {
        $containersToModel = $this->getContainersToModel();
        $modelName = $containersToModel[get_class($container)];
        return new $modelName();
    }

    protected function getModelAttributesByContainer($container)
    {
        $class = get_class($container);
        $modelAttributes = $this->getConverter($class)->convertContainerToModelAttributes($container);
        return $modelAttributes;
    }

    protected function getCriteriaByParams($modelAttributes, $container, $params = array())
    {
        $class = get_class($container);
        $converter = $this->getConverter($class);
        $strictCompareAttributes = $params['strictCompareAttributes'];
        $params['strictCompareAttributes'] = $converter->convertAttributesNamesToModelAttributesNames($strictCompareAttributes);
        $rangeAttributes = $params['rangeAttributes'];
        $params['rangeAttributes'] = $converter->convertAttributesNamesToModelAttributesNames($rangeAttributes);
        $range = $params['range'];
        $params['range'] = $converter->convertArrayWithContainerAttributesToArrayWithModelAttributes($range);
        $criteria = new CDbCriteria;
        $criteriaParams = array();
        foreach ($modelAttributes as $key => $value) {
            if($value != NULL){
                if(in_array($key,$params['strictCompareAttributes'])){
                    $criteria->addCondition("`$key` = :$key");
                    $criteriaParams[":$key"] = $value;
                }else{
                    $criteria->compare($key, $value, true);
                }
            }
        }
        foreach ($params['range'] as $param => $range) {
            if (in_array($param, $params['rangeAttributes'])) {
                if (isset($range['min'])) {
                    $criteria->addCondition("`$param` >= :$param" . "_min");
                    $criteriaParams["$param" . "_min"] = $range['min'];
                }
                if (isset($range['max'])) {
                    $criteria->addCondition("`$param` <= :$param" . "_max");
                    $criteriaParams["$param" . "_max"] = $range['max'];
                }
            }
        }
        if($criteriaParams){
            $criteria->params = CMap::mergeArray($criteria->params, $criteriaParams);
        }
        $defaultOrderParam = (property_exists($container, 'id'))? array('id'=>'asc') : null;
        $orderParameter = (isset($params['order'])) ? $params['order'] : $defaultOrderParam;

        if ($orderParameter) {
            foreach ($orderParameter as $containerAttribute => $sort) {
                $modelAttribute = $converter->convertAttributesNamesToModelAttributesNames(array($containerAttribute));
                unset($orderParameter[$containerAttribute]);
                if ($modelAttribute && array_key_exists($modelAttribute[0], $modelAttributes)) {
                    $orderParameter[$modelAttribute[0]] = $sort;
                }
            }
            $ordersArray = array();
            foreach ($orderParameter as $modelAttribute => $sort) {
                $ordersArray[] = "$modelAttribute $sort";
            }
            $criteria->order = implode(',', $ordersArray);
        }
        if (isset($params['limit'])) {
            $criteria->limit = $params['limit'];
        }
        if (isset($params['offset'])) {
            $criteria->offset = $params['offset'];
        }

        $pk = array('id');
        if (isset($params['pk'])) {
            if (is_array($params['pk'])) {
                $pk = $params['pk'];
            } elseif (is_string($params['pk'])) {
                $pk = array($params['pk']);
            }
        }
        $pk = $converter->convertAttributesNamesToModelAttributesNames($pk);
        foreach ($pk as &$key) {
            $key = '`' . $key . '`';
        }
        $criteria->select = implode(',', $pk);

        return $criteria;
    }

    public function searchIDsByContainerCriteria($container, $params = array())
    {
        $modelAttributes = $this->getModelAttributesByContainer($container);
        $model = $this->getModelRelatedToContainer($container);
        $criteria = $this->getCriteriaByParams($modelAttributes, $container, $params);
        $rows = $model->findAll($criteria);
        $IDs = array();
        $pk = $this->getConverter(get_class($container))->convertAttributesNamesToModelAttributesNames($params['pk']);

        if (count($pk) == 1) {
            foreach ($rows as $row) {
                $IDs[] = $row->attributes[$pk[0]];
            }
        } else {
            foreach ($rows as $row) {
                $key = array();
                foreach ($pk as $fieldName) {
                    $key[$fieldName] = $row->attributes[$fieldName];
                }
                $IDs[] = $key;
            }
        }

        return $IDs;
    }

    public function getTotalCountForContainer($container, $params = array())
    {
        $modelAttributes = $this->getModelAttributesByContainer($container);
        $model = $this->getModelRelatedToContainer($container);
        $criteria = $this->getCriteriaByParams($modelAttributes, $container, $params);
        $rowsCount = $model->count($criteria);
        if(!$rowsCount){
            return 0;
        }
        return $rowsCount;
    }
}
