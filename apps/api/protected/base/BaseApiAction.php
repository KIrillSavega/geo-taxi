<?php

class BaseApiAction extends CAction
{
    const ERR_SYSTEM = 500;
    const ERR_VALIDATION = 400;
    const ERR_AUTH = 401;
    
    public $rawRequestInputAtAutodoc = false;
    
     public $errorDictionary = array(
        self::ERR_SYSTEM => 'Server error.',
        self::ERR_VALIDATION => 'Validation error.',
        self::ERR_AUTH => 'Not authorized. Correct session_id required.',
    );
    
    public $model;
    
    public $errors = array();
    
    public function rules()
    {
        return array();
    }
    
    public function getDescriptionForAutodoc()
    {
        return "";
    }
    
    protected function beforeRun( $params )
    {
        if( $this->validate($params) ){
            return true;
        }
        else {
            $this->renderError( self::ERR_VALIDATION, $this->errors );
        }
    }
    
    public function validate($params)
    {
        $this->model = new ApiRequestModel( $this->rules() );
        $this->model->setAttributes($params);
        $apiKey = $this->getApiKeyFromRequest();
        $validated = false;
        if( $apiKey != Yii::app()->params->apiKey ){
            $this->errors = array(
                'X_API_KEY' => array('X_API_KEY in http headers is invalid'),
            );
        } else {
            $validated = $this->model->validate();
            $this->errors = $this->model->getErrors();
        }
        return $validated;
    }
    
    public function runWithParams( $params ) 
    {
        if( $this->beforeRun( $params ) ){
            return parent::runWithParams( $this->model->getAttributes() );
        }
    }
    
    public function renderSuccess( $resource = '' )
    {
        $this->encodeAndRender($resource);
    }
    
    public function renderError( $code, $resource = null )
    {
        $validationErrorMessage = $code == self::ERR_VALIDATION ? $this->validationErrorsToString($resource) : null;
        $this->encodeAndRender( array( 'error' => array(
            'code' => $code,
            'message' => $this->errorDictionary[$code],
            'validationMessage' => $validationErrorMessage,
            'resource' => $resource
        )));
    }
    
    protected function validationErrorsToString($errors)
    {
        $result = '';
        foreach( $errors as $error ){
            foreach( $error as $message ){
                $result .= $message.' ';
            }
        }
        return trim($result);
    }


    protected function encodeAndRender( $output )
    {
        $formatter = $this->getController()->getFormatter();
        if(method_exists($formatter, 'setRootTag') ){
            $rootTag = $this->getObjectAndMethodName('Response');
            $rootTag = str_replace('-', '', $rootTag);
            $formatter->setRootTag( $rootTag );
        }
        $encoded = $formatter->encode($output);
        $level = is_array($output) && isset($output['error']) ? CLogger::LEVEL_WARNING : CLogger::LEVEL_INFO;
        Yii::log("API response: $encoded", $level, "webservice");
        $formatter->sendHttpHeaders();
        echo $encoded;
        Yii::app()->end();
    }
    
    public function getObjectAndMethodName( $postfix = '' )
    {
        $objectName = strtolower($this->controller->getId());
        $methodName = strtolower($this->getId());
        $methodName = ucfirst($methodName);
        return $objectName.$methodName.$postfix;
    }
    
    protected function getApiKeyFromRequest()
    {
        return isset($_SERVER['HTTP_X_API_KEY']) ? $_SERVER['HTTP_X_API_KEY'] : null;
    }
}