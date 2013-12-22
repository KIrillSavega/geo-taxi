<?php

function app()
{
    return Yii::app();
}

Yii::setPathOfAlias('core', dirname(__FILE__) . DIRECTORY_SEPARATOR . '..');
Yii::setPathOfAlias('bootstrap', dirname(__FILE__).DIRECTORY_SEPARATOR.'../extensions/bootstrap');


return array(
    'id' => 'geo-taxi',
    'basePath' => dirname(__FILE__) . '/../cli',
    'name' => 'Geo-Taxi',
    'import' => array(
        'core.components.*',
        'core.helpers.*',
        'core.components.base.*',
        'core.extensions.*',
        'core.extensions.mail.YiiMailMessage',
    ),
    'components' => array(
        'bootstrap'=>array(
            'class'=>'bootstrap.components.Bootstrap',
        ),

        'db' => require(dirname(__FILE__) . '/db.php'),

        'mail' => require(dirname(__FILE__) . '/mail.php'),

        'location' => array(
            'class' => 'core.components.location.Location',
            'dbClass' => 'db',
        ),
    ),
    'params' => require(dirname(__FILE__) . '/params.php'),
);
