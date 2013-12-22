<?php

class UniqueBrandNameValidator extends CValidator
{
    public function validateAttribute($object, $attribute)
    {
        $brandName = $object->{$attribute};
        $brand = Yii::app()->brand->getByName($brandName);
        if ($brand && ($brand->id != $object->id)) {
            $this->addError($object, $attribute, 'Brand with this name already created');
        }
    }
}

