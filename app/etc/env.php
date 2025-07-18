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
                'host' => 'localhost',
<<<<<<< HEAD
                'dbname' => 'dev4_db',
                'username' => 'dev4_db',
=======
                'dbname' => 'bm_db',
                'username' => 'bm_db',
>>>>>>> a4d3a9c (bm)
                'password' => 'B0NbGOSJg',
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
    'MAGE_MODE' => 'developer',
    'session' => [
        'save' => 'files'
    ],
    'cache' => [
        'frontend' => [
            'default' => [
                'id_prefix' => '19e_'
            ],
            'page_cache' => [
                'id_prefix' => '19e_'
            ]
        ]
    ],
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
        'compiled_config' => 1,
<<<<<<< HEAD
        'ec_cache' => 0
=======
        'ec_cache' => 1
>>>>>>> a4d3a9c (bm)
    ],
    'install' => [
        'date' => 'Wed, 01 Nov 2017 21:41:06 +0000'
    ],
    'lock' => [
        'provider' => 'db',
        'config' => [
            'prefix' => ''
        ]
    ],
    'downloadable_domains' => [
        'benem.nl'
    ],
    'system' => [
        'default' => [
            'csp' => [
                'mode' => [
                    'storefront_checkout_index_index' => [
                        'report_only' => '1'
                    ],
                    'admin_sales_order_create_index' => [
                        'report_only' => '1'
                    ]
                ],
                'policies' => [
                    'storefront_checkout_index_index' => [
                        'scripts' => [
                            'inline' => '1'
                        ]
                    ],
                    'admin_sales_order_create_index' => [
                        'scripts' => [
                            'inline' => '1'
                        ]
                    ]
                ]
            ]
        ]
    ]
];
