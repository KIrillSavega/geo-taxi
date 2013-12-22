<?php

class EmployeeTestCase extends MultiDbCDbTestCase
{
    public $components = array(
        'employee',
    );


    public $fixtureManagers = array(
         'fixtureEmployee' => array(
             'employee' => array('loadOnFirstSetUpOnly' => true),
             'employee_permission_group' => array('loadOnFirstSetUpOnly' => true),
         ),
     );

     public $fixtures = array(
         'employee' => '\core\components\employee\models\Employee',
         'employee_permission_group' => '\core\components\employee\models\EmployeePermissionGroup',
     );
}