<?php

defined('STDIN') or define('STDIN', fopen('php://stdin', 'r'));
defined('YII_DEBUG') or define('YII_DEBUG', true);

require_once('../yiiframework/yii.php');
$config = require_once('config/main.php');
$app = Yii::createConsoleApplication($config);

Yii::import('system.cli.commands.*');
$app->commandRunner->addCommands(YII_PATH . '/cli/commands');
$env = @getenv('YII_CONSOLE_COMMANDS');
if (!empty($env))
    $app->commandRunner->addCommands($env);
$app->run();
