<?php

return CMap::mergeArray(
        require(dirname(__FILE__) . '/../../../../core/config/main.php'),
        array(
            'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
            'name' => 'api',
            'import' => array(
                'application.base.*',
                'application.base.formatters.*',
                'application.components.*',
                'application.extensions.*',
            ),
            'defaultController' => 'documentation',
            'preload' => array('i18n'),
            'sourceLanguage' => 'en',
            'components' => array(
                'errorHandler' => array(
                    'errorAction' => 'documentation/error',
                ),
                'urlManager' => require(dirname(__FILE__) . '/urlManager.php'),
                'user' => array(
                    'class' => 'ApiWebUser',
                    'autoRenewCookie' => true,
                    'allowAutoLogin' => true,
                ),
                'apiSession' => array(
                    'class' => 'ApiRedisSession',
                    'sessionLifeTime' => 60*60*24,
                ),
            ),
        )
    );