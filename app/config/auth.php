<?php

return [


    'defaults' => [
        'guard' => 'api',
        'passwords' => 'users',
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
        'api' => [
            'driver' => 'jwt',
            'provider' => 'users',
        ],

        'admin' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => \Domain\User\Models\User::class,
        ],

        // 'users' => [
        //     'driver' => 'database',
        //     'table' => 'users',
        // ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,

    'admin' => [
        'name' => env('ADMIN_NAME', 'admin'),
        'email' => env('ADMIN_EMAIL', 'admin@admin.com'),
        'password' => env('ADMIN_PASSWORD', 'password'),
    ],
    'exchange_user' => [
        'name' => env('EXCHANGE_NAME', 'exchange'),
        'email' => env('EXCHANGE_EMAIL', 'admin_exchange@admin.com'),
        'password' => env('EXCHANGE_PASSWORD', 'password'),
        'ttl' => env('EXCHANGE_JWT_TTL', 43200),
        'refresh_ttl' => env('EXCHANGE_JWT_REFRESH_TTL', 129600)
    ],
];
