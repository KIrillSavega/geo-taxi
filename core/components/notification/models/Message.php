<?php

namespace core\components\notification\models;
class Message extends \MessageGii
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function afterDelete()
    {
        parent::afterDelete();
    }

    public function afterSave()
    {
        parent::afterSave();
    }

    public function rules()
    {
        return \CMap::mergeArray(parent::rules(), array(
            array('user_id', 'core.validators.customer.CustomerExistValidator'),
        ));
    }
}
