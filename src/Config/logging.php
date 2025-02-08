<?php


return [

    /*
    |--------------------------------------------------------------------------
    | Additional Log Channels
    |--------------------------------------------------------------------------
    |
    | This option adds extra log channels to the app
    |
    |
    */
    'channels' => [
        'test-queue' => [
            'driver' => 'single',
            'path' => storage_path('logs/test-queue.log'),
            'level' => 'debug',
        ],
    ]
];