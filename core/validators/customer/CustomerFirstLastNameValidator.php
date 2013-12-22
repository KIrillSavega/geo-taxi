<?php

class CustomerFirstLastNameValidator extends CValidator
{
    public function validateAttribute($object, $attribute)
    {
        if (preg_match('/[^[:alnum:][:space:]]/u', $object->{$attribute}) == true) {
            $this->addError($object, $attribute, '{attribute} should consist of characters and digits only.');
        }
    }
}
