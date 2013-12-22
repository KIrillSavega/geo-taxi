<?php

class LocationTestCase extends MultiDbCDbTestCase
{
    public $components = array(
        'location',
    );

    public $fixtureManagers = array(
        'fixtureLocation' => array(
            'address' => array('loadOnFirstSetUpOnly' => true),
        ),
    );

    public $fixtures = array(
        'address' => '\core\components\location\models\Address',
    );

    public function setUp()
    {
        parent::setUp();
    }
}
?>
