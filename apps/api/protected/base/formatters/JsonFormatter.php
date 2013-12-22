<?php

class JsonFormatter implements IFormatter
{
    public function decode($input) 
    {
        return CJSON::decode($input);
    }
    
    public function encode($output) 
    {
        return CJSON::encode($output);
    }
    
    public function sendHttpHeaders() 
    {
        header('Content-type: application/json');
    }
}
