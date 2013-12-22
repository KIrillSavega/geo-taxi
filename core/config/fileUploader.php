<?php
return array(
    'class' => 'core.components.fileUploader.FileUploader',
    'uploaderClassName' => 'LocalFilesystemUploader',
    'fileStorageUrl' => isset($_SERVER['HTTP_HOST'])?'http://'.$_SERVER['HTTP_HOST'].'/comcash-company/apps/storage':'http://localhost/comcash-company/apps/storage',
    'uploaderOptions' => array(
    'uploadFolder' => '/var/www/comcash-company/apps/storage',
    ),
);
