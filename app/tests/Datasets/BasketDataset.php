<?php

use Illuminate\Support\Carbon;


dataset('set delivery data valid', function () {
    return [
        'valid data' =>
            [
                fn() => [
                    'request' => [
                        'cityId' => $this->city->id,
                        "deliveryParams" => [
                            [
                                'storeId' => $this->store->id,
                                'deliveryType' => 'pickup',
                                'deliverySubType' => 'other',
                                'date' => Carbon::tomorrow()->format('Y-m-d'),
                                'time' => '10_20'
                            ]
                        ]
                    ],
                ]
            ],
    ];
});

dataset('set delivery empty data', function () {
    return [
        'empty data' =>
            [
                fn() => [
                    'request' => [],
                    'error' => [
                        'cityId' => ['validation.required'],
                    ],
                ]
            ],
    ];
});

dataset('set delivery data invalid', function () {
    return [
        'invalid types data' =>
            [
                fn() => [
                    'request' => [
                        'cityId' => 10,
                        "deliveryParams" => [
                            [
                                'storeId'           => 'storeId',
                                'deliveryType'      => 2,
                                'deliverySubType'   => 2,
                                'date'              => 2,
                                'time'              => 2,
                            ]
                        ]
                    ],
                    'error' => [
                        'cityId' => [
                            "validation.exists"
                        ],
                        'deliveryParams' => [
                            [
                                'date' => [
                                    'validation.string',
                                    'validation.date_format',
                                    'validation.after'
                                ],
                                'deliverySubType' => [
                                    'validation.string',
                                    'validation.in'
                                ],
                                'deliveryType' => [
                                    'validation.string',
                                    'validation.in'
                                ],
                                'storeId' => [
                                    'validation.integer'
                                ],
                                'time' => [
                                    'validation.string'
                                ]
                            ]
                        ]
                    ],
                ]
            ],
    ];
});

dataset('clear basket invalid', function () {
    return [
        'invalid types data' =>
            [
                fn() => [
                    'request' => [
                        'date'              => 2,
                        'onlyUnavailable'   => 2,
                    ],
                    'error' => [
                        'date'              => [
                            'validation.string',
                            "validation.date_format",
                        ],
                        'onlyUnavailable'   => ['validation.boolean'],
                    ],
                ]
            ],
    ];
});
