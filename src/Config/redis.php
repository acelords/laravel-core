<?php

$keys = [];

$redisExtras = [
    ['table' => 'domains', 'roles' => ['admin', 'sudo']],
    ['table' => 'organisations', 'roles' => ['admin', 'sudo']],
    ['table' => 'roles', 'roles' => ['admin', 'sudo']],
    ['table' => 'permissions', 'roles' => ['admin', 'sudo']],

    'themes', 'configurations', 'seos',
];

$models = [
    //
];

return [
    /*
    |--------------------------------------------------------------------------
    | Redis Configuration
    |--------------------------------------------------------------------------
    |
    | This option controls redis
    |
    |
    */

    /**
     * Redis prefix to facilitate searches on a module and creation of database builder
     * For example, within redis configuration templates, we execute the method DB::table(settings_*)
     * Prefix is appended to items store within the redis server
     */
    'prefix' => 'settings',

    'keys' => $keys,

    'extras' => $redisExtras,

    'models' => $models,

    /**
     * Attached to redis in order to identify and distinguish the application
     */
    'application_prefix' => env('REDIS_PREFIX', 'acelords_webapps_'),

    /**
     * Specify Configuration Model to use.
     * Replace with AceLords Project Namespace
     */
    'defaults' => [
        'models' => [
            'configuration' => AceLords\Core\Models\Configuration::class,
        ],

        'tables' => [
            'configurations' => 'configurations',
        ]
    ],

];
