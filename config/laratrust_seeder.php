<?php

return [
    'role_structure' => [
        'super_administrator' => [
            'role' => 'v,c,u',
            'user' => 'v,c,u,d',
            'project' => 'v,c,u,d',
            'position' => 'v,c,u,d',
            'file-type' => 'v,c,u,d',
            'forecast' => 'v,c,u,d',
            'scheduling' => 'v,c,u,d',
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
        'vd' => 'void',
        'vf' => 'verify',
        'vl' => 'validate',
        'cs' => 'create-schedule',
        'cl' => 'cancel',
    ],
];

// php artisan db:seed --class=LaratrustSeeder
