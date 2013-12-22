<?php

class ApiRequestModel extends DynamicAttributesModel
{
    protected $rules;
    
    public function __construct($rules) 
    {
        $this->rules = $rules;
    }
    
    public function rules() 
    {
        return $this->rules;
    }
}
