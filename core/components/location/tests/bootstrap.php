<?php
$yiit = dirname(__FILE__) . '/../../../../yiiframework/yiit.php';
$config = dirname(__FILE__) . '/../../../../core/config/tests.php';
require_once($yiit);
Yii::createWebApplication($config);