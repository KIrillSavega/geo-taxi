<?php
return array(
    'class' => 'core.components.notification.Notification',
    'queueStorageComponent' => 'redisPersistentStorage',
    'queueClass' => 'QueueStorage',
    'dbClass' => 'NotificationDb',
    'numberOfEmailsSentAtATime' => 100,
    'numberOfEmailsInPortion' => 10,
    'emailFrom' => 'test_spamer@mobidev.biz',
);