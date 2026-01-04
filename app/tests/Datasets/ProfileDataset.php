<?php

use Tests\Unit\User\Profile\ProfileHelper;

dataset('update profile valid', function () {
    return [
        'valid' =>
            [
                fn() => [
                    'request' => ProfileHelper::getUpdateProfileRequest(),
                ]
            ],
    ];
});

dataset('update phone invalid', function () {
    return [
        'invalid' =>
            [
                fn() => [
                    'request' => [
                        'phone' => 'phone',
                    ],
                    'error' => [
                        'phone' => [
                            'validation.regex'
                        ],
                    ],
                ]
            ],
    ];
});

dataset('update phone empty data', function () {
    return [
        'empty data' =>
            [
                fn() => [
                    'request' => [],
                    'error' => [
                        'phone' => [
                            'validation.required',
                        ],
                    ],
                ]
            ],
    ];
});

dataset('check phone empty data', function () {
    return [
        'empty data' =>
            [
                fn() => [
                    'request' => [],
                    'error' => [
                        'phone' => ['validation.required'],
                        'code' => ['validation.required'],
                        'signature' => ['validation.required'],
                    ],
                ]
            ],
    ];
});

dataset('check phone invalid', function () {
    return [
        'invalid' =>
            [
                fn() => [
                    'request' => [
                        'phone' => 0,
                        'code' => 0,
                        'signature' => 0,
                    ],
                    'error' => [
                        'message' => 'Код неверен',
                    ],
                ]
            ],
    ];
});
