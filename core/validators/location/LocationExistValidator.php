<?php
class LocationExistValidator extends CValidator
{
    public function validateAttribute($object, $attribute)
    {
        $locationId = $object->{$attribute};
        if ($locationId && !Yii::app()->location->getAddressById($locationId)) {
            $this->addError($object, $attribute, "Location # $locationId does not exist!");
        }
    }
}