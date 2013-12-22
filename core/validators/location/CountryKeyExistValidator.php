<?php
class CountryKeyExistValidator extends CValidator
{
    public function validateAttribute($object, $attribute)
    {
        $code = $object->{$attribute};

        if (!empty($code) && !Yii::app()->location->getCountryNameByCode($code)) {
            $this->addError($object, $attribute, "Country with code \"$code\" is not found!");
        }
    }
}