<?php

class GenderValidator extends CRangeValidator
{
    public $range = array(Customer::GENDER_MALE, Customer::GENDER_FEMALE);
    public $allowEmpty = false;
}
