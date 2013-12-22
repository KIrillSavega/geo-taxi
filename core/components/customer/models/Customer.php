<?php

namespace core\components\customer\models;
class Customer extends \CustomerGii
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function rules()
    {
        return \CMap::mergeArray(parent::rules(), array(
            array('private_email', 'email'),
            array('id', 'numerical', 'integerOnly'=>true),
            array('mobile_phone', 'core.validators.customer.UniqueCustomerPhoneValidator'),
            array('mobile_phone', 'core.validators.PhoneNumberValidator'),
            array('password', 'length', 'min' => 6),
            array('first_name', 'core.validators.customer.CustomerFirstLastNameValidator'),
            array('last_name', 'core.validators.customer.CustomerFirstLastNameValidator'),
        ));
    }
}
