<?php
return [
    'backend' => [
        'frontName' => '9rlorgkx'
    ],
    'crypt' => [
        'key' => '9f635dc8c2c9a1b21643351892f6b595'
    ],
    'db' => [
        'table_prefix' => 'mg_',
        'connection' => [
            'default' => [
                'host' => 'mysqlmaster',
                'dbname' => 'benem_mg1',
                'username' => 'benem_mg1',
                'password' => 'R~88ek4b6dt)tu~x)t]20)#9',
                'active' => '1'
            ]
        ]
    ],
    'resource' => [
        'default_setup' => [
            'connection' => 'default'
        ]
    ],
    'x-frame-options' => 'SAMEORIGIN',
    'MAGE_MODE' => 'production',
    'cache_types' => [
        'config' => 1,
        'layout' => 1,
        'block_html' => 1,
        'collections' => 1,
        'reflection' => 1,
        'db_ddl' => 1,
        'eav' => 1,
        'customer_notification' => 1,
        'full_page' => 1,
        'config_integration' => 1,
        'config_integration_api' => 1,
        'translate' => 1,
        'config_webservice' => 1,
        'compiled_config' => 1
    ],
    'install' => [
        'date' => 'Wed, 01 Nov 2017 21:41:06 +0000'
    ],
    'session' => [
        'save' => 'files'
    ]
];
