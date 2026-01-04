<?php

return [

    'date_format' => env('DATE_FORMAT', 'd-m-Y'),
    'time_format' => env('TIME_FORMAT', 'H:i:s'),
    'datetime_format' => env('DATE_FORMAT', 'd-m-Y') . ' ' . env('TIME_FORMAT', 'H:i:s'),

    'domain' => env('DASHBOARD_DOMAIN', null),

    'prefix' => env('DASHBOARD_PREFIX', '/admin'),

    'middleware' => [
        'public'  => ['web'],
        'private' => ['web', 'platform'],
    ],

    'guard' => 'admin',

    'auth'  => true,

    'index' => 'platform.main',

    'resource' => [
        'stylesheets' => [
            '/css/admin/style.css',
            '/css/app.css',
        ],
        'scripts'     => [
            '/js/dashboard.js',
            '/js/app.js',
        ],
    ],

    'template' => [
        'header' => 'vendor.platform.partials.header',
        'footer' => 'footer',
    ],

    'attachment' => [
        'disk'      => 'public',
        'generator' => \Orchid\Attachment\Engines\Generator::class,
    ],

    'icons' => [
        'orc' => \Orchid\IconPack\Path::getFolder(),
    ],

    'notifications' => [
        'enabled'  => true,
        'interval' => 60,
    ],

    'search' => [
        // \App\Models\User::class
    ],

    'turbo' => [
        'cache' => false
    ],

    'fallback' => true,

    'provider' => \App\Orchid\PlatformProvider::class,

    'permissions' => [
        'exchange' => [
            'slug' => 'exchange',
            'name' => 'Доступ к обмену с 1С'
        ],
        'content' => [
            'slug' => 'content',
            'name' => 'Доступ к контенту сайта'
        ],
        'feedback' => [
            'slug' => 'feedback',
            'name' => 'Обратная связь'
        ],
        'review' => [
            'slug' => 'review',
            'name' => 'Отзывы'
        ],
        'activate_store' => [
            'slug' => 'activate_store',
            'name' => 'Активность магазинов'
        ],
    ],
    'yandex_map_api_key' => env('YANDEX_MAP_API_KEY', ''),

    'dark_stores_notify_email_1' => env('DARK_STORES_NOTIFY_EMAIL_1', ''),
    'dark_stores_notify_email_2' => env('DARK_STORES_NOTIFY_EMAIL_2', ''),
    'dark_stores_notify_email_3' => env('DARK_STORES_NOTIFY_EMAIL_3', '')
];
