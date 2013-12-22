<?php

class BrandNameValidator extends CValidator
{
    public function validateAttribute($object, $attribute)
    {
        if (preg_match('/[^[:lower:][:digit:]|-]/', $object->{$attribute}) == true) {
            $this->addError($object, $attribute, '{attribute} should consist only of lower case latin characters, digits or sign \'-\'.');
        }
    }
}
