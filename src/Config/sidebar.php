<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Sidebar Generator Class
    |--------------------------------------------------------------------------
    |
    | This option controls the class that handles the sidebar
    | The sidebar is then fetched as "SidebarGenerator::get('sudo')" or something
    */
    'class' => 'App\Library\Sidebars\SidebarGenerator',

    /*
    |--------------------------------------------------------------------------
    | Example Module sidebar.php
    |--------------------------------------------------------------------------
    |
    | This option controls the class that handles the sidebar
    | The sidebar is then fetched as "SidebarGenerator::get('sudo')" or something
    */

    // 'general' => [
    //     [
    //         'name' => 'My Samples',
    //         'icon' => 'fa fa-file-word-o',
    //         'route' => 'admin.my.samples.index',
    //         'permissions' => 'admin.samples.index',
    //     ],
    //     [
    //         'name' => 'Admin Functions',
    //         'icon' => 'settings',
    //         'order' => 20,
    //         'permissions' => 'admin.*',

    //         'children' => [
    //             [
    //                 'name' => 'All Promotions',
    //                 'route' => 'admin.promotions.index',
    //                 'permissions' => 'admin.promotions.index',
    //             ],
    //         ],
    //     ],
    // ],

    // 'sudo' => [
    //     [
    //         'name' => 'Trash',
    //         'icon' => 'delete',
    //         'children' => [
    //             [
    //                 'name' => 'Users',
    //                 'route' => 'admin.trash.users.index',
    //             ],
    //         ],
    //     ]
    // ]
];
