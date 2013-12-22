<?php

// this contains the application url rules
return array(
    'urlFormat' => 'path',
    'showScriptName' => false,
    'rules' => array(
        'object/<id>' => array('documentation/object'),
        'object/<objectId>/method/<methodId>' => array('documentation/method'),
        
        '<format:\w+>/authorization/login' => array('authorization/login'),
        'authorization/login/' => array('authorization/login'),
        '<format:\w+>/authorization/logout' => array('authorization/logout'),
        'authorization/logout/' => array('authorization/logout'),

        '<format:\w+>/location/countries' => array('location/countrylist'),
        'location/countries' => array('location/countrylist'),
    ),
);
