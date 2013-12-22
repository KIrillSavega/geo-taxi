<?php

interface IFormatter
{
    public function encode($output);
    public function decode($input);
    public function sendHttpHeaders();
}

class BaseApiController extends CController
{
    public $formats = array(
        'json' => 'JsonFormatter',
    );
    
    public $defaultFormat = 'json';
    
    private $_formatter = null;
    
    public function getActionParams()
    {
        $rawPostData = Yii::app()->request->getRawBody();
        $decodedPost = $this->getFormatter()->decode($rawPostData);
        return CMap::mergeArray($decodedPost, $_GET);
    }
    
    public function runActionWithFilters($action, $filters) 
    {
        $url = Yii::app()->getRequest()->getUrl();
        $post = Yii::app()->request->getRawBody();
        Yii::log("API request: '$url'. POST body: $post", CLogger::LEVEL_INFO, "webservice");
        try{
            if( !($action instanceof BaseApiAction) ){
                throw new CException( "Requested Api action must be extended from BaseApiAction class" );
            }
            parent::runActionWithFilters($action, $filters);
        } catch( Exception $e ){
            $action->renderError( $action::ERR_SYSTEM, $e->getMessage() );
        }
    }
    
    
    /**
     *
     * @return IFormatter 
     */
    public function getFormatter()
    {
        if( is_object($this->_formatter) ){
            return $this->_formatter;
        } else {
            $shouldThrowException = false;
            $formats = $this->formats;
            $format = isset($_GET['format']) && !empty($_GET['format']) ? $_GET['format'] : $this->defaultFormat;
            if( !key_exists($format, $formats) ){
                $shouldThrowException = true;
                $format = $this->defaultFormat;
            }
            $class = $formats[$format];
            $this->_formatter = new $class();
            if( $shouldThrowException ){
                throw new CException( "This format is not supported. Available: ". implode(',',array_keys($this->formats))  );
            }
            return $this->_formatter;
        }
    }
}
