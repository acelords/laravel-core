<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Basic Site Configuration
    |--------------------------------------------------------------------------
    |
    | This option controls the different themes contained in the system
    |
    |
    */
    'under_maintenance' => env('APP_UNDER_MAINTENANCE', false),
    'under_maintenance_message' => env('APP_UNDER_MAINTENANCE_MESSAGE'),
    'site_theme_key' => 'site_theme',

    'sys' => [
        'access_token' => 'ju#$980[123]@13`!i2b',
        'product_key' => env('APP_PRODUCT_KEY', 'XXMKSNCHEAPMULTINIIUEOWMNSAONBOAWQOXX'),
        'product_key_length' => 28,
        'version' => env('APP_PRODUCT_VERSION', '4.0'),
        'codename' => 'AceYuvo',
    ],

    /*
    |--------------------------------------------------------------------------
    | System paginations
    |--------------------------------------------------------------------------
    |
    | This option controls the different paginations within the system
    |
    */
    'pagination' => [
        'xs' => 5,
        's' => 10,
        'm' => 25,
        'l' => 50,
        'xl' => 100,
        'p' => 7
    ],

    /*
    |--------------------------------------------------------------------------
    | Product Key
    |--------------------------------------------------------------------------
    |
    | This key is used by the System to autodetect the validity of a product key
    |
    */
    'product_key' => env('APP_PRODUCT_KEY'),
    'product_key_length' => 28,

    /*
    |--------------------------------------------------------------------------
    | Backend Theming System Features
    |--------------------------------------------------------------------------
    |
    | This option controls the different themes contained in the system
    |
    |
    */
    'backendtheme' => [
        'admin' => env('ACELORDS_BACKENDTHEME_ADMIN', 'vuetify'),
        'client' => env('ACELORDS_BACKENDTHEME_CLIENT', 'vuetify'),
        'others' => env('ACELORDS_BACKENDTHEME_OTHERS', 'null'),
    ],

];