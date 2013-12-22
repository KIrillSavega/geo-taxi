<?php

class UniqueCustomerEmailValidator extends CValidator
{
    public function validateAttribute($object, $attribute)
    {
        $email = $object->{$attribute};
        $customerId = Yii::app()->customer->getIdByEmail($email);
        if (!$customerId) {
            return;
        }
        $customerContainer = Yii::app()->customer->getById($customerId);
        $modelCustomerId = in_array('id', $object->attributeNames()) ? $object->id : 0;
        if ($customerContainer) {
            if ($customerContainer->id != $modelCustomerId && !is_null($customerContainer->id)) {
                $this->addError($object, $attribute, 'This email has already been registered');
            }
        }
    }
}

