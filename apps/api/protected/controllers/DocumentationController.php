<?php

class DocumentationController extends CController
{
    public $layout = 'documentation';

    public $breadcrumbs = null;

    public function filters()
    {
        return array('debugOnly');
    }

    public function filterDebugOnly($filterChain)
    {
        if (YII_DEBUG) {
            $filterChain->run();
        } else {
            throw new CHttpException(403, 'Access denied');
        }
    }


    public function actionIndex()
    {
        $this->breadcrumbs = null;
        $objects = array();
        $controllers = $this->getApiControllers();
        foreach ($controllers as $id => $controller) {
            $objects[] = array(
                'label' => $id,
                'url' => $this->createUrl('object', array('id' => $id))
            );
        }
        $baseApiAction = new BaseApiAction('test-controller', 'base-api-action');
        $baseApiController = new BaseApiController('base-api-controller');

        $errors = array();
        foreach ($baseApiAction->errorDictionary as $code => $description) {
            $errors[] = array(
                'id' => $code,
                'description' => $description
            );
        }

        $this->render('index', array(
            'objects' => $objects,
            'defaultFormat' => $baseApiController->defaultFormat,
            'supportedFormats' => implode(',', array_keys($baseApiController->formats)),
            'errorDictionaryDataProvider' => new CArrayDataProvider($errors),
        ));
    }

    public function actionObject($id)
    {
        $controllers = $this->getApiControllers();
        if (isset($controllers[$id])) {
            $controller = $controllers[$id];
            $actions = $this->getApiActionsByController($controller);
            $methods = array();
            foreach ($actions as $actionId => $action) {
                $methods[] = array(
                    'label' => $actionId,
                    'url' => $this->createUrl('method', array('methodId' => $actionId, 'objectId' => $id))
                );
            }
            $this->breadcrumbs = array($id);
            $this->render('object', array(
                'objectName' => $id,
                'methods' => $methods,
            ));
        } else {
            throw new CHttpException(404);
        }
    }

    public function actionMethod($objectId, $methodId)
    {
        $controllers = $this->getApiControllers();
        if (isset($controllers[$objectId])) {
            $controller = $controllers[$objectId];
            $actions = $this->getApiActionsByController($controller);
            if (isset($actions[$methodId])) {
                $action = $actions[$methodId];
                $rules = $action->rules();
                $methodDescription = $action->getDescriptionForAutodoc();
                $methodUrls = array();
                foreach ($controller->formats as $format => $formatter) {
                    $methodUrls[$format] = $this->createAbsoluteUrl(strtolower($objectId . '/' . $methodId), array('format' => $format));
                }
                $session_id = isset($_POST['session_id']) ? $_POST['session_id'] : null;
                $requestParams = isset($_POST['params']) ? $_POST['params'] : array();
                $requestParams = array_filter($requestParams);
                $request = null;
                $response = null;
                $url = null;
                $requestFormat = null;
                $timeInMicroseconds = null;
                if (!empty($requestParams) || isset($_POST['request_format'])) {
                    $requestFormat = $_POST['request_format'];
                    $url = $methodUrls[$requestFormat];
                    $url .= $session_id ? '?session_id=' . urlencode($session_id) : '';
                    $objectName = strtolower($objectId);
                    $methodName = strtolower($methodId);
                    $methodName = ucfirst($methodName);
                    if ($action->rawRequestInputAtAutodoc) {
                        $request = isset($requestParams['Raw_Encoded_Input']) ? $requestParams['Raw_Encoded_Input'] : null;
                    } else {
                        $rootTag = $objectName . $methodName . 'Request';
                        $formatterClass = $controller->formats[$requestFormat];
                        $formatter = new $formatterClass();
                        if (method_exists($formatter, 'setRootTag')) {
                            $formatter->setRootTag($rootTag);
                        }
                        $request = !empty($requestParams) ? $formatter->encode($requestParams) : '';
                    }
                    $timeStart = microtime(true);
                    $testQuery = $this->doTestQuery($url, $request);
                    $timeInMicroseconds = microtime(true) - $timeStart;
                    $request = htmlentities($testQuery['request']);
                    $response = htmlentities($testQuery['response']);
                }
                $this->breadcrumbs = array(
                    $objectId => $this->createUrl('object', array('id' => $objectId)),
                    $methodId,
                );
                $this->render('method', array(
                    'rules' => $this->getRulesTable($rules),
                    'objectName' => $objectId,
                    'methodName' => $methodId,
                    'methodUrls' => $methodUrls,
                    'requestParams' => $this->getRequestParams($action, $rules, $requestParams),
                    'request' => $request,
                    'response' => $response,
                    'formats' => array_keys($controller->formats),
                    'session_id' => $session_id,
                    'requestUrl' => $url,
                    'requestFormat' => $requestFormat,
                    'methodDescription' => $methodDescription,
                    'queryTime' => $timeInMicroseconds,
                ));
            } else {
                throw new CHttpException(404);
            }
        } else {
            throw new CHttpException(404);
        }
    }

    protected function doTestQuery($url, $postdata)
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'X_API_KEY: ' . Yii::app()->params->apiKey,
        ));
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        $response = curl_exec($curl);
        curl_close($curl);
        return array(
            'request' => $postdata,
            'response' => $response,
        );
    }

    protected function getRulesTable($rules)
    {
        $validationTable = '<table class="items table table-bordered"> <tr> <th>Parameter</th> <th>Validator</th> <th>Validator Settings</th> </tr> ';
        foreach ($rules as $rule) {
            $validationTable .= '<tr><td>' . $rule[0] . '</td><td>' . $rule[1] . '</td>';
            $i = 0;
            $validationRule = '';
            foreach ($rule as $key => $detail) {
                if ($i > 1) {
                    if (is_string($key)) {
                        $validationRule .= $key . ': ';
                    }
                    if (is_array($detail)) {
                        //$detail =  !is_array($detail) ? trim(addslashes($detail)) : '';
                        $detail .= CJSON::encode($detail);
                    } elseif (is_bool($detail)) {
                        $detail = $detail ? 'true' : 'false';
                    }
                    $validationRule .= $detail . '; ';
                }
                $i++;
            }
            $validationTable .= '<td>' . $validationRule . '</td></tr>';
        }
        $validationTable .= '</table>';
        return $validationTable;
    }

    protected function getRequestParams(BaseApiAction $action, $rules, $formData = null)
    {
        if ($action->rawRequestInputAtAutodoc) {
            $result['Raw_Encoded_Input'] = isset($formData['Raw_Encoded_Input']) ? $formData['Raw_Encoded_Input'] : null;
        } else {
            $result = array();
            $keys = array();
            foreach ($rules as $rule) {
                $keys[] = $rule[0];
            }
            $keys = array_unique($keys);
            foreach ($keys as $key) {
                $result[$key] = isset($formData[$key]) ? $formData[$key] : null;
            }
        }
        return $result;
    }


    protected function getApiActionsByController(BaseApiController $controller)
    {
        $actions = array();
        $actionAlliases = $controller->actions();
        foreach ($actionAlliases as $id => $alias) {
            $exploded = explode('.', $alias);
            $class = end($exploded);
            $path = Yii::getPathOfAlias($alias);
            require_once($path . '.php');
            $action = new $class($controller, $id);
            if ($action instanceof BaseApiAction) {
                $actions[$id] = $action;
            }
        }
        return $actions;
    }

    protected function getApiControllers()
    {
        $controllerPath = Yii::app()->getControllerPath();
        $controllerPaths = CFileHelper::findFiles($controllerPath, array(
            'fileTypes' => array('php'),
            'level' => 0,
        ));
        $controllers = array();
        foreach ($controllerPaths as $path) {
            require_once($path);
            $exploded = explode(DIRECTORY_SEPARATOR, $path);
            $filename = end($exploded);
            $class = substr($filename, 0, -4);
            $id = substr($class, 0, -10);
            $object = new $class($id);
            if ($object instanceof BaseApiController) {
                $controllers[$id] = $object;
            }
        }
        return $controllers;
    }

    public function actionError()
    {
        echo '404 API method was not found';
    }
}
