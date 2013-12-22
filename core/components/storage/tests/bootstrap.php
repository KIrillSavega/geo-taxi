<?php
$yiit = dirname(__FILE__) . '/../../../../yiiframework/yiit.php';
$config = dirname(__FILE__) . '/../../../../core/config/tests.php';
require_once($yiit);
Yii::createWebApplication($config);
Yii::import('core.components.storage.models.*');
Yii::import('core.components.storage.models.gii.*');
Yii::import('core.components.storage.components.*');
