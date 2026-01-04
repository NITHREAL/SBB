<?php

return [
    'admin' => [
        'first_name'    => env('ADMIN_NAME', 'admin'),
        'email'         => env('ADMIN_EMAIL', 'admin@admin.com'),
        'password'      => env('ADMIN_PASSWORD', 'admin'),
    ],
    'test_user' => [
        'first_name'    => env('TEST_USER_NAME', 'Иван'),
        'middle_name'    => env('TEST_USER_MIDDLE_NAME', 'Иванович'),
        'last_name'    => env('TEST_USER_LAST_NAME', 'Иванов'),
        'phone'    => env('TEST_USER_PHONE', '9000000000'),
        'email'         => env('TEST_USER_EMAIL', 'test_user@test.com'),
        'password'      => env('TEST_USER_PASSWORD', 'testUser'),
        'birthdate'      => env('TEST_USER_BIRTHDATE', '2000-01-01'),
        'bonuses'      => env('TEST_USER_BONUSES', '1000'),
    ],
];
