<?php
class CorrectCustomerPasswordValidator extends CValidator
{
    public function validateAttribute($object, $attribute)
    {
        $password = $object->{$attribute};
        $passwordHash = UserHelper::hashPassword($password);
        $customer = Yii::app()->customer->getById(Yii::app()->user->id);
        if (!$customer) {
            return;
        }
        if (!empty($password) && ($passwordHash !== $customer->password)) {
            $this->addError($object, $attribute, "Sorry password is incorrect!");
        }
    }
}