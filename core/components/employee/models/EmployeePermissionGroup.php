<?php

namespace core\components\employee\models;
class EmployeePermissionGroup extends \EmployeePermissionGroupGii
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function rules()
    {
        return \CMap::mergeArray(parent::rules(), array(
            array('id', 'numerical'),
        ));
    }
}
