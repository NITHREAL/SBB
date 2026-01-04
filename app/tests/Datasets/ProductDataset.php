<?php

use Illuminate\Support\Arr;

dataset('get not exist product', function () {
    return [
        'not exist' =>
            [
                fn() => [
                    'error' => [
                        'message' => 'товара с slug: not_exist не существует',
                    ],
                ],
            ]
    ];
});

dataset('create review product', function () {
    return [
        'valid data' =>
            [
                fn() => [
                    'request' => [
                        'slug'      => $this->product->slug,
                        'text'      => 'some text',
                        "rating"    => 5,
                    ],
                ]
            ],
    ];
});

dataset('create review product empty data', function () {
    return [
        'empty data' =>
            [
                fn() => [
                    'request'   => ['slug'      => $this->product->slug],
                    'error'     => ['rating'    => ['validation.required']]
                ],
            ]
    ];
});

dataset('create review product invalid data', function () {
    return [
        'invalid types data' =>
            [
                fn() => [
                    'request' => [
                        'slug'      => $this->product->slug,
                        'text'      => 5,
                        "rating"    => 'some text',
                        ],
                    'error' => [
                        'text'      => ['validation.string'],
                        "rating"    => [
                            'validation.integer',
                            'validation.max.numeric'
                        ],
                    ]
                ],
            ],
    ];
});

dataset('search product', function () {
    return [
        'valid data' =>
            [
                fn() => [
                    'request' => [
                        'search'        => Arr::first(explode(" ", $this->product->title)),
                        "storeOneCId"   => $this->store->system_id,
                    ],
                ]
            ],
    ];
});


