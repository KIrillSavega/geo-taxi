<?php

class UniqueCustomerPhoneValidator extends CValidator
{
    public function validateAttribute($object, $attribute)
    {
        $phone = $object->{$attribute};
        $customerId = Yii::app()->customer->getIdByPhone($phone);
        if (!$customerId) {
            return;
        }
        $customerContainer = Yii::app()->customer->getById($customerId);
        $modelUserId = isset($object->id) ? $object->id : 0;
        if ($customerContainer) {
            if ($customerContainer->id != $modelUserId && !is_null($customerContainer->id)) {
                $this->addError($object, $attribute, 'This phone number has already been registered');
            }
        }
    }
}
