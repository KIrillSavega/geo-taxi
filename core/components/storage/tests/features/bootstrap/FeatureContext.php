<?php

class FeatureContext extends BaseComponentContext
{
    public static $suiteName = 'storage';
    public $testCaseClassName = 'StorageTestCase';

    /**
     * @Given /^result should match to "([^"]*)" based on "([^"]*)" container with prefix "([^"]*)"$/
     */
    public function resultShouldMatchToBasedOnContainerWithPrefix($pathType, $containerName, $prefix)
    {
        if (!isset($this->containers[$containerName])) {
            throw new Exception("'$containerName' container not found in containers array.");
        }

        $fileContainer = $this->containers[$containerName];
        if (!($fileContainer instanceof FileContainer)) {
            throw new Exception("'$containerName' container is not an instance of FileContainer.");
        }

        $path = Yii::app()->storage->settings[$fileContainer->pathId]['path'];
        $pathToFile = $path . DIRECTORY_SEPARATOR . $prefix . $fileContainer->uid . '.' . $fileContainer->ext;
        switch ($pathType) {
            case 'url':
                $compareString = Yii::app()->fileUploader->fileStorageUrl . $pathToFile;
                break;
            case 'path':
                $compareString = Yii::app()->fileUploader->uploaderOptions['uploadFolder'] . $pathToFile;
                break;
            default:
                throw new Exception("'$pathType' path type is unknown.");
        }

        if ($this->result != $compareString) {
            throw new Exception("Result '$this->result' is not equal to '$pathType' path '$compareString'");
        }
    }

    /**
     * @Given /^new record from container should be created in "([^"]*)" table with PK "([^"]*)"$/
     */
    public function newRecordFromContainerShouldBeCreatedInTableWithPk($table, $primaryKey)
    {
        $dao = $this->getDbConnection()->createCommand();
        $resultFromDb = $dao->select($primaryKey)
            ->from($table)
            ->where("$primaryKey=:id", array(':id'=>$this->result->{$primaryKey}))
            ->queryRow();

        if ($resultFromDb[$primaryKey] != $this->result->{$primaryKey}) {
            throw new Exception('New record was not created in table ' . $table);
        }
    }

    /**
     * @Given /^"([^"]*)" field from "([^"]*)" table with PK "([^"]*)" is equal to "([^"]*)" and equal to "([^"]*)" field of returned container$/
     */
    public function fieldFromTableWithPkIsEqualToAndEqualToFieldOfReturnedContainer($fieldInDb, $table, $primaryKey, $value, $containerField)
    {
        $containerName = get_class($this->result);
        $dao = $this->getDbConnection()->createCommand();
        $resultFromDb = $dao->select('*')
            ->from($table)
            ->where("$primaryKey=:id", array(':id'=>$this->result->{$primaryKey}))
            ->queryRow();

        $containerFromDb = $this->getComponent()->getDb()->getConverter($containerName)->convertArrayToContainer($resultFromDb);

        if ($containerFromDb->{$fieldInDb} != $value) {
            throw new Exception($containerFromDb->{$fieldInDb} . ' not equal to ' . $value);
        }

        if ($containerFromDb->{$fieldInDb} != $this->result->{$containerField}) {
            throw new Exception($containerFromDb->{$fieldInDb} . ' not equal to ' . $this->result->{$containerField});
        }
    }

    /**
     * @Given /^"([^"]*)" field from "([^"]*)" table with PK "([^"]*)" is equal to "([^"]*)" field of returned container$/
     */
    public function fieldFromTableWithPkIsEqualToFieldOfReturnedContainer($fieldInDb, $table, $primaryKey, $containerField)
    {
        $containerName = get_class($this->result);
        $dao = $this->getDbConnection()->createCommand();
        $resultFromDb = $dao->select('*')
            ->from($table)
            ->where("$primaryKey=:id", array(':id'=>$this->result->{$primaryKey}))
            ->queryRow();

        $containerFromDb = $this->getComponent()->getDb()->getConverter($containerName)->convertArrayToContainer($resultFromDb);

        if ($containerFromDb->{$fieldInDb} != $this->result->{$containerField}) {
            throw new CException($containerFromDb->{$fieldInDb} . ' not equal to ' . $this->result->{$containerField});
        }
    }

    /**
     * @When /^I call method from subcomponent "([^"]*)"$/
     */
    public function iCallMethodFromSubcomponent($subComponent)
    {

        spl_autoload_register(array('YiiBase', 'autoload'));
        try {
            $this->result = call_user_func_array(array(Yii::app()->{$this->component}->{$subComponent}(), $this->method), array_values($this->parameters));
            $this->exception = null;
        } catch (Exception $e) {
            $this->exception = $e;
        }
        spl_autoload_unregister(array('YiiBase', 'autoload'));
    }

}