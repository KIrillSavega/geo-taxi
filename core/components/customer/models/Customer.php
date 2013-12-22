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
            array('company_email', 'core.validators.customer.UniqueCustomerEmailValidator'),
            array('company_email', 'email'),
            array('private_email', 'email'),
            array('id, pos_pin_code', 'numerical', 'integerOnly'=>true),
            array('mobile_phone', 'core.validators.customer.UniqueCustomerPhoneValidator'),
            array('mobile_phone', 'core.validators.PhoneNumberValidator'),
            array('password', 'length', 'min' => 6),
            array('first_name', 'length', 'min' => 1),
            array('pos_pin_code', 'length', 'is' => 4),
            array('pos_pin_code', 'match', 'pattern' => '/^([\d]{4})$/'),
            array('pos_pin_code', 'core.validators.customer.UniqueCustomerPinValidator'),
            array('last_name', 'length', 'min' => 1),
            array('first_name', 'core.validators.customer.CustomerFirstLastNameValidator'),
            array('last_name', 'core.validators.customer.CustomerFirstLastNameValidator'),
            array('company_id', 'core.validators.company.CompanyExistValidator'),
            array('status', 'numerical', 'min' => 1, 'max' => 2)
        ));
    }
}
