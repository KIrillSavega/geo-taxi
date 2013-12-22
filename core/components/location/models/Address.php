<?php

namespace core\components\location\models;
class Address extends \AddressGii
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
            array('postal_code', 'length', 'allowEmpty' => false, 'min' => 4),
            array('postal_code', 'match', 'pattern'=>"/^[a-zA-Z0-9\-\s]+$/"),
            array('country', 'length', 'is'=>2),
            array('country', 'core.validators.location.CountryKeyExistValidator'),
        ));
    }
}
