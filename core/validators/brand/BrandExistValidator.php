<?php
class BrandExistValidator extends CValidator
{
    public function validateAttribute($object, $attribute)
    {
        $brandId = $object->{$attribute};
        if (!empty($brandId) && !Yii::app()->brand->getById($brandId)) {
            $this->addError($object, $attribute, "Brand with id $brandId does not exist!");
        }
    }
}