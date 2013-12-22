<?php

class StorageTestCase extends MultiDbCDbTestCase
{
    public $components = array(
        'storage',
    );

    public $fixtureManagers = array(
        'fixtureStorage' => array(
            'file'          => array('loadOnFirstSetUpOnly' => true),
            'image2product' => array('loadOnFirstSetUpOnly' => true),
            'image2sales_outlet_gallery' => array('loadOnFirstSetUpOnly' => true),
        ),
    );

    public $fixtures = array(
        'file'          => '\core\components\storage\models\File',
        'image2product' => '\core\components\storage\models\Image2Product',
        'image2sales_outlet_gallery' => '\core\components\storage\models\Image2SalesOutletGallery',
    );

    public function setUp()
    {
        parent::setUp();
    }
}
?>
