<?php

return [
    'role_structure' => [
        'super_administrator' => [
            'role' => 'v,c,u',
            'user' => 'v,c,u,d',
            'leave' => 'v,c,u,d,p,a,r,cl',
            'master' => 'v,c,u,d',
            'forecast' => 'v,c,u,d',
            'schedule' => 'v,c,u,d',
            'report' => 'v,c,u,d',
        ],
    ],
    'permission_structure' => [],
    'permissions_map' => [
        'v' => 'view',
        'c' => 'create',
        'u' => 'update',
        'd' => 'delete',
        'p' => 'process',
        'a' => 'approve',
        'r' => 'reject',
        'cl' => 'cancel',
    ],
];

// php artisan db:seed --class=LaratrustSeeder
