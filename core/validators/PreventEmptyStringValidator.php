<?php
class PreventEmptyStringValidator extends CValidator
{
    public function validateAttribute($object, $attribute)
    {
        $value = $object->{$attribute};
        if ((trim($value) === '') && ($value !== null) ) {
            $this->addError($object, $attribute, "Value should not be empty string");
        }
    }
}