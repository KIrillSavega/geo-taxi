<?php
class BirthdayValidator extends CValidator
{
    public function validateAttribute($object, $attribute)
    {
        $birthday = $object->{$attribute};
        if ($birthday) {
            $date = new DateTime();
            $dateDiff = date_diff(new DateTime('now'), $date->setTimestamp($birthday));
            if($dateDiff->y < 5){
                $this->addError($object, $attribute, "Customer has to be older than 16 years!");
            }
        }
    }
}