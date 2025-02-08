<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Namespace
    |--------------------------------------------------------------------------
    |
    | This option specifies the core namespace
    |
    |
    */
    'namespace' => 'AceLords',

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
        'codename' => env('APP_PRODUCT_CODENAME', 'AceLords WebApp'),
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
    'backend_theme' => [
        'admin' => env('ACELORDS_BACKENDTHEME_ADMIN', 'snowflake'),
        'client' => env('ACELORDS_BACKENDTHEME_CLIENT', 'snowflake'),
        'others' => env('ACELORDS_BACKENDTHEME_OTHERS', 'null'),
    ],

    'spa' => [
        'dashboard' => env('ACELORDS_BACKENDTHEME_ADMIN', 'vuetify') . '.layouts.main'
    ],

    /*
    |--------------------------------------------------------------------------
    | System passport configuration
    |--------------------------------------------------------------------------
    |
    | This option provides basic config for passport
    |
    */
    'passport' => [
        'grant_type' => 'password',
        'client_id' => env('PASSPORT_CLIENT'),
        'client_secret' => env('PASSPORT_TOKEN'),
        'scope' => ''
    ],

    /*
    |--------------------------------------------------------------------------
    | Seeder Generator
    |--------------------------------------------------------------------------
    |
    | Used when seeding to create the permissions in a readable format from the seed classes
    | For example:- view all users | create a user | update a user | delete a user
    |
    */
    'resources' => [
        'index' => 'View all %s',
        
        'create' => 'Create a %s',
        
        'update' => 'Update a %s',
        
        'destroy' => 'Delete a %s',
    ],

];