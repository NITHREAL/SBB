<?php

$sberbankDebug = !empty(env('SBERBANK_DEBUG'));

return [

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'smsru' => [
        'api_id'    => env('SMSRU_API_ID'),
        'sender'    => env('SMSRU_SENDER', 'box'),
        'debug'     => (bool) env('SMSRU_DEBUG', false),
        'host'      => env('SMSRU_HOST', 'https://sms.ru'),
        'extra'     => [
            // any other API parameters
            // 'tinyurl' => 1
        ],
    ],

    'captcha' => [
        'host'    => env('RECAPTCHA_HOST'),
        'secret'  => env('RECAPTCHA_SECRET_KEY'),
    ],

    'appmetrica' => [
        'token'     => env('APPMETRICA_TOKEN'),
        'app_id'    => env('APPMETRICA_APP_ID'),
    ],

    'yookassa' => [
        'shop_id'           => env('YOOKASSA_SHOP_ID'),
        'secret_key'        => env('YOOKASSA_SECRET_KEY'),
        'base_endpoint'     => env('YOOKASSA_BASE_ENDPOINT'),
        'test_shop_id'      => env('YOOKASSA_TEST_SHOP_ID'),
        'test_secret_key'   => env('YOOKASSA_TEST_SECRET_KEY'),
        'test_mode'         => env('YOOKASSA_TEST_MODE', false),
    ],

    'sberbank'  => [
        'login'         => $sberbankDebug ? env('SBERBANK_DEBUG_LOGIN') : env('SBERBANK_LOGIN'),
        'password'      => $sberbankDebug ? env('SBERBANK_DEBUG_PASSWORD') : env('SBERBANK_PASSWORD'),
        'debug'         => $sberbankDebug,
    ],

    'manzana' => [
        'customer'              => env('MANZANA_CUSTOMER_SERVICE', 'https://62.152.55.186:8636/CustomerOfficeService'),
        'manager'               => env('MANZANA_MANAGER_SERVICE', 'https://62.152.55.186:8637/ManagerOfficeService'),
        'administrator'         => env('MANZANA_ADMINISTRATOR_SERVICE', 'https://62.152.55.186:8635/AdministratorOfficeService'),
        'processing'            => env('MANZANA_PROCESSING_SERVICE', 'https//62.152.55.186:8634/POSProcessing.asmx'),
        'partner_id'            => env('MANZANA_PARTNER_ID', 'E0B50EAC-A9E2-EC11-910B-00155DF93230'),
        'test_sms_code'         => env('MANZANA_TEST_SMS_CODE', '6742'),
        'virtual_card_type_id'  => env('MANZANA_VIRTUAL_CARD_TYPE_ID', '57935596-2D54-ED11-910D-00155DF93230'),
    ],
];
