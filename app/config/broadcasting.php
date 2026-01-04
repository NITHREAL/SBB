<?php

return [

    'default' => env('BROADCAST_DRIVER', 'redis'),

    'connections' => [

        'redis' => [
            'driver' => 'redis',
            'connection' => 'default',
            'queue' => env('REDIS_QUEUE', 'redis'),
            'retry_after' => 90,
            'block_for' => null,
        ],

        'pusher' => [
            'driver' => 'pusher',
            'key' => env('SOKETI_DEFAULT_APP_KEY', 'soketi'),
            'secret' => env('SOKETI_DEFAULT_APP_SECRET', 'soketi'),
            'app_id' => env('SOKETI_DEFAULT_APP_ID', 'soketi'),
            'options' => [
                'host' => env('PUSHER_HOST', 'socket'),
                'port' => env('PUSHER_PORT', 6001),
                'scheme' => env('PUSHER_SCHEME', 'http'),
                'encrypted' => true,
                'useTLS' => false,
            ],
            'client_options' => [
                'verify' => false,
            ]
        ],

        'ably' => [
            'driver' => 'ably',
            'key' => env('ABLY_KEY'),
        ],

        'log' => [
            'driver' => 'log',
        ],

        'null' => [
            'driver' => 'null',
        ],

    ],

];
