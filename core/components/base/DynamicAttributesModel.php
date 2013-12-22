<?php

class DynamicAttributesModel extends CFormModel
{
    public $attributes;
    
    public function __get($name) 
    {
        if( isset($this->attributes[$name]) ){
            return $this->attributes[$name];
        }
    }
    
    public function __set($name, $value)
    {
        $this->attributes[$name] = $value;
    }
    
    public function __isset($name)
    {
        return isset($this->attributes[$name]);
    }
    
    public function __unset($name)
    {

        if( isset($this->attributes[$name]) ){
             unset($this->attributes[$name]);       
        }
        if( isset($this->attributes->$name) ){
             unset($this->$name);       
        }
    }

    public function getAttributes( $names=null )
    {
        return $this->attributes;
    }
    
    public function setAttributes($values,$safeOnly=true)
    {
        if(!is_array($values)){
            return;
        }     
        foreach($values as $name=>$value){
            $this->$name=$value;
        }
    }
}
