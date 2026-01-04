<?php

return [
    'headers'       => [
        'favorite'  => env('HEADER_FAVORITE_TOKEN', 'Bit-Favorite-Token'),
        'buyer'     => env('HEADER_BUYER_TOKEN', 'Bit-Buyer-Token'),
    ],

    'front_url' => env('FRONT_URL', 'http://localhost'),
    'card_binding_url' => env('CARD_BINDING_URL', 'http://localhost'),

    'buyer'         => [
        'token_ttl'             => env('BUYER_TOKEN_TTL', 86400),
        'delivery_interval_ttl' => env('BUYER_DELIVERY_INTERVAL_TTL', 3600),
    ],
    'notifications' => [
        'push'  => [
            'available_time'    => [
                'start' => env('PUSH_NOTIFICATION_AVAILABLE_START_TIME', '07:00'),
                'end'   => env('PUSH_NOTIFICATION_AVAILABLE_END_TIME', '23:00'),
            ],
        ],
    ],

    'cdn' => [
      'cdn_url' => env('CDN_URL'),
    ],

    'acquiring' => [
        'type'  => env('ACQUIRING', 'yookassa'),
        'debug' => env('ACQUIRING_DEBUG', false),
        'ttl'   => env('ACQUIRING_TTL', 86400),
    ],

    'sbermarket' => [
        'url'   => env('SBERMARKET_URL', 'https://api.sbermarket.ru/v3/notifications'),
        'token' => env('SBERMARKET_TOKEN', '09b52f53-bcfb-464f-818d-2ea91cb3f087'),
    ],

    'payment_online' => [
        'init_amount' => env('PAYMENT_ONLINE_INIT_AMOUNT', 20)
    ],

    'loyalty'  => [
        'type'  => env('LOYALTY_TYPE', 'manzana'),
    ],
];
