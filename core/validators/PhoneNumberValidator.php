<?php
class PhoneNumberValidator extends CValidator
{
    public $pattern = '/^([\d]{0,11})$/';

    public function validateAttribute($object, $attribute)
    {
        $phone = $object->{$attribute};
        if (!empty($phone) && !preg_match($this->pattern, $phone)) {
            $this->addError($object, $attribute,
                "Phone Number has wrong format. It must be in International format. E.g. 17774445555");
        }
    }
}