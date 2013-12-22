<?php
class CustomerExistValidator extends CValidator
{
    public function validateAttribute($object, $attribute)
    {
        $customerId = $object->{$attribute};
        if (!empty($customerId) && !Yii::app()->customer->getById($customerId)) {
            $this->addError($object, $attribute, "Customer with id $customerId does not exist!");
        }
    }
}