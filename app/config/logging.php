<?php

use Monolog\Handler\NullHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\SyslogUdpHandler;

return [

    'default' => env('LOG_CHANNEL', 'stack'),

    'deprecations' => env('LOG_DEPRECATIONS_CHANNEL', 'null'),

    'channels' => [
        'stack' => [
            'driver' => 'stack',
            'channels' => ['single'],
            'ignore_exceptions' => false,
        ],

        'single' => [
            'driver' => 'single',
            'path' => storage_path('logs/laravel.log'),
            'level' => env('LOG_LEVEL', 'debug'),
        ],

        'daily' => [
            'driver' => 'daily',
            'path' => storage_path('logs/laravel.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'days' => 7,
        ],

        'slack' => [
            'driver' => 'slack',
            'url' => env('LOG_SLACK_WEBHOOK_URL'),
            'username' => 'Laravel Log',
            'emoji' => ':boom:',
            'level' => env('LOG_LEVEL', 'critical'),
        ],

        'papertrail' => [
            'driver' => 'monolog',
            'level' => env('LOG_LEVEL', 'debug'),
            'handler' => SyslogUdpHandler::class,
            'handler_with' => [
                'host' => env('PAPERTRAIL_URL'),
                'port' => env('PAPERTRAIL_PORT'),
            ],
        ],

        'stderr' => [
            'driver' => 'monolog',
            'level' => env('LOG_LEVEL', 'debug'),
            'handler' => StreamHandler::class,
            'formatter' => env('LOG_STDERR_FORMATTER'),
            'with' => [
                'stream' => 'php://stderr',
            ],
        ],

        'syslog' => [
            'driver' => 'syslog',
            'level' => env('LOG_LEVEL', 'debug'),
        ],

        'errorlog' => [
            'driver' => 'errorlog',
            'level' => env('LOG_LEVEL', 'debug'),
        ],

        'null' => [
            'driver' => 'monolog',
            'handler' => NullHandler::class,
        ],

        'emergency' => [
            'path' => storage_path('logs/laravel.log'),
        ],

        'payment' => [
            'driver'        => 'daily',
            'path'          => storage_path('logs/payment/payment.log'),
            'level'         => 'debug',
            'permission'    => 0777,
            'days'          => 5,
        ],

        'sbermarket' => [
            'driver' => 'single',
            'path' => storage_path('logs/sbermarket.log'),
            'level' => 'debug',
        ],

        'message' => [
            'driver' => 'daily',
            'path' => storage_path('logs/exchange/message.log'),
            'level' => 'debug',
            'days' => 3,
            'permission' => 0777,
        ],

        'yookassa' => [
            'driver' => 'daily',
            'path' => storage_path('logs/yookassa/yookassa.log'),
            'level' => 'debug',
            'days' => 3,
            'permission' => 0777,
        ],

        'sberbank' => [
            'driver' => 'daily',
            'path' => storage_path('logs/sberbank/sberbank.log'),
            'level' => 'debug',
            'days' => 3,
            'permission' => 0777,
        ],

        'loyalty'   => [
            'driver' => 'daily',
            'path' => storage_path('logs/loyalty/loyalty.log'),
            'level' => 'debug',
            'days' => 7,
            'permission' => 0777,
        ],

        // Exchanges
        'exchange' => [
            'category' => [
                'driver' => 'daily',
                'path' => storage_path('logs/exchange/categories/category.log'),
                'level' => 'debug',
                'days' => 5,
            ],

            'product' => [
                'driver' => 'daily',
                'path' => storage_path('logs/exchange/products/products.log'),
                'level' => 'debug',
                'days' => 5,
            ],

            'onec' => [
                'driver' => 'daily',
                'path' => storage_path('logs/exchange/onec/onec.log'),
                'level' => 'debug',
                'days' => 5,
            ],

            'leftover' => [
                'driver' => 'daily',
                'path' => storage_path('logs/exchange/leftovers/leftover.log'),
                'level' => 'debug',
                'days' => 5,
            ],

            'region' => [
                'driver' => 'daily',
                'path' => storage_path('logs/exchange/regions/region.log'),
                'level' => 'debug',
                'days' => 5,
            ],

            'city' => [
                'driver' => 'daily',
                'path' => storage_path('logs/exchange/cities/city.log'),
                'level' => 'debug',
                'days' => 5,
            ],

            'store' => [
                'driver' => 'daily',
                'path' => storage_path('logs/exchange/stores/store.log'),
                'level' => 'debug',
                'days' => 5,
            ],

            'order' => [
                'driver' => 'daily',
                'path' => storage_path('logs/exchange/orders/order.log'),
                'level' => 'debug',
                'days' => 5,
            ],

            'order_confirm' => [
                'driver' => 'daily',
                'path' => storage_path('logs/exchange/orders/order_confirm.log'),
                'level' => 'debug',
                'days' => 5,
            ],

            'unit' => [
                'driver' => 'daily',
                'path' => storage_path('logs/exchange/units/unit.log'),
                'level' => 'debug',
                'days' => 5,
            ],

            'rabbit' => [
                'driver' => 'daily',
                'path' => storage_path('logs/exchange/rabbit/rabbit.log'),
                'level' => 'debug',
                'days' => 5,
            ],
        ],
    ],

];
