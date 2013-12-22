<?php

function app()
{
    return Yii::app();
}

Yii::setPathOfAlias('core', dirname(__FILE__) . DIRECTORY_SEPARATOR . '..');
Yii::setPathOfAlias('storage', dirname(__FILE__) . DIRECTORY_SEPARATOR . '../../apps/storage');
Yii::setPathOfAlias('bootstrap', dirname(__FILE__).DIRECTORY_SEPARATOR.'../extensions/bootstrap');


return array(
    'id' => 'geo-taxi',
    'basePath' => dirname(__FILE__) . '/../cli',
    'name' => 'Geo Taxi',
    'import' => array(
        'core.containers.*',
        'core.components.*',
        'core.helpers.*',
        'core.components.base.*',
        'core.components.autocompleteIDE.*',
        'core.components.fileUploader.instances.*',
        'core.components.cache.*',
        'core.extensions.*',
        'core.extensions.mail.YiiMailMessage',
    ),
    'components' => array(
        'bootstrap'=>array(
            'class'=>'bootstrap.components.Bootstrap',
        ),
        //db connections:
        'dbLocation' => require(dirname(__FILE__) . '/db.php'),
        'dbCustomer' => require(dirname(__FILE__) . '/db.php'),

        //redis
        'redisCache' => require(dirname(__FILE__) . '/redisCache.php'),
        'redisPersistentStorage' => require(dirname(__FILE__) . '/redisPersistentStorage.php'),

        //cache components:
        'fileCache' => array(
            'class' => 'core.components.cache.FileCacheComponent',
            'cachePath' => dirname(__FILE__) . '/../runtime/cache'
        ),
        'dummyCache' => array(
            'class' => 'core.components.cache.DummyCacheComponent',
        ),

        // -------------------------------------

        'session' => array(
            'class' => 'core.components.base.RedisSessionManager',
            'autoStart' => true,
            'cookieMode'=>'only',
            'sessionName' => 'session',
            'saveHandler'=>'redis',
            'savePath' => 'tcp://localhost:6379?database=2&prefix=session::',
            'timeout' => 28800, //8h
        ),

        'assetManager' => require( dirname(__FILE__) . '/assetManager.php' ),

        'purifier' => array(
            'class' => 'core.extensions.purifier.Purifier',
        ),

        'csvImporter' => array(
            'class' => 'core.extensions.csvimporter.CsvImporter',
        ),

        'imageLib' => array(
            'class'=>'core.extensions.imageLibrary.CImageComponent',
            // GD or ImageMagick
            'driver'=>'GD',
            // ImageMagick setup path
            'params'=>array('directory'=>'/opt/local/bin'),
        ),

        'fileUploader' => require( dirname(__FILE__) . '/fileUploader.php' ),

        'storage' => array(
            'class' => 'core.components.storage.Storage',
            'cacheComponent' => 'redisCache',
            'cacheClass' => 'StorageCache',
            'dbClass' => 'StorageDb',
            'settings' => require( dirname(__FILE__) . '/storageSettings.php' ),
        ),

        'notification' => require(dirname(__FILE__) . '/notification.php'),

        'mail' => require(dirname(__FILE__) . '/mail.php'),
        
        'location' => array(
            'class' => 'core.components.location.Location',
            'cacheComponent' => 'redisCache',
            'cacheClass' => 'LocationCache',
            'dbClass' => 'LocationDb',
        ),

        'customer' => array(
            'class' => 'core.components.customer.Customer',
            'cacheComponent' => 'redisCache',
            'cacheClass' => 'CustomerCache',
            'dbClass' => 'CustomerDb',
        ),
    ),
    'params' => require(dirname(__FILE__) . '/params.php'),
);
