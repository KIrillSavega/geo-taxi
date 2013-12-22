<?php

class CustomerWithPhoneExistValidator extends CValidator
{
    public function validateAttribute($object, $attribute)
    {
        $phone = $object->{$attribute};
        if (!empty($phone) && !Yii::app()->customer->getIdByPhone($phone)) {
            $this->addError($object, $attribute, "Customer with this phone number is not registered!");
        }
    }
}

