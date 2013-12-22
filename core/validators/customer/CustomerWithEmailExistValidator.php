<?php

class CustomerWithEmailExistValidator extends CValidator
{
    public function validateAttribute($object, $attribute)
    {
        $email = $object->{$attribute};
        if (!empty($email) && !Yii::app()->customer->getIdByEmail($email)) {
            $this->addError($object, $attribute, "Customer with this e-mail is not registered!");
        }
    }
}

