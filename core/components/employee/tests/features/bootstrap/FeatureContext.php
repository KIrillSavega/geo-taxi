<?php

class FeatureContext extends BaseComponentContext
{
    public static $suiteName = 'employee';
    public $testCaseClassName = 'EmployeeTestCase';

    /**
     * @Given /^password "([^"]*)" should be hashed by "([^"]*)" algorithm with "([^"]*)" salt$/
     */
    public function passwordShouldBeHashedByAlgorithmWithSalt($password, $algorythm, $salt)
    {
        if ($this->result->password != hash_hmac($algorythm, $password, $salt)) {
            throw new Exception('Password is not hashed');
        }
    }

    /**
     * @Given /^result "([^"]*)" fixture should be equal to EmployeePermissionGroup container$/
     */
    public function resultFixtureShouldBeEqualToEmployeepermissiongroupContainer($fixtureId)
    {
        $fixtureResult = $this->phpUnit->employee_permission_group[$fixtureId];
        $fixtureArrayResult = array('id' => $fixtureResult['id'], 'title' => $fixtureResult['title']);
        $permissionFromFixture = CJSON::decode($fixtureResult['permissions']);
        foreach ($permissionFromFixture as $key => $value) {
            $fixtureArrayResult[$key] = $value;
        }
        $resultDb = (array)$this->result;
        $resultDbToFixture = array_diff($resultDb, $fixtureArrayResult);
        $resultFixtureToDb = array_diff($fixtureArrayResult, $resultDb);
        if (!empty($resultFixtureToDb) || !empty($resultDbToFixture)) {
            throw new Exception('Given objects of EmployeePermissionGroup are not equal');
        }
    }

    /**
     * @Given /^all records from "([^"]*)" in employee fixtures should be equal to array of containers EmployeePermissionGroupContainer$/
     */
    public function allRecordsFromInEmployeeFixturesShouldBeEqualToArrayOfContainersEmployeepermissiongroupcontainer($fixtureIdsString)
    {
        $fixtureIDs = explode(';', $fixtureIdsString);
        foreach ($fixtureIDs as $k => $fixtureId) {
            $fixtureResult = $this->phpUnit->employee_permission_group[$fixtureId];
            $fixtureArrayResult = array('id' => $fixtureResult['id'], 'title' => $fixtureResult['title']);
            $permissionFromFixture = CJSON::decode($fixtureResult['permissions']);
            foreach ($permissionFromFixture as $key => $value) {
                $fixtureArrayResult[$key] = $value;
            }
            $resultDb = (array)$this->result[$k];;
            $resultDbToFixture = array_diff($resultDb, $fixtureArrayResult);
            $resultFixtureToDb = array_diff($fixtureArrayResult, $resultDb);
            if (!empty($resultFixtureToDb) || !empty($resultDbToFixture)) {
                print_r($resultFixtureToDb);
                print_r($resultDbToFixture);
                throw new Exception('Given objects of EmployeePermissionGroup are not equal');
            }

        }
    }

    /**
     * @Given /^result should consist of employee with id "([^"]*)" first and last names separated by space$/
     */
    public function resultShouldConsistOfEmployeeWithIdFirstAndLastNamesSeparatedBySpace( $id )
    {
        $employee = Yii::app()->employee->getById( $id );

        $str = $employee->firstName." ".$employee->lastName;

        if ( $this->result != $str )
        {
            throw new Exception(  );
        }
    }
}