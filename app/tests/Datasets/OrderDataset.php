<?php

use Tests\Unit\Order\OrderHelper;


dataset('create order', function () {
    return [
        'valid data' =>
            [
                fn() => [
                    'request' => OrderHelper::getCreateOrderRequest($this->store),
                    'mockData' => OrderHelper::getMockBasketData($this->store, $this->product),
                ]
            ],
    ];
});

dataset('check basket', function () {
    return [
        'valid data' =>
            [
                fn() => [
                    'request' => OrderHelper::getCreateOrderRequest($this->store),
                    'mockData' => OrderHelper::getMockBasketData($this->store, $this->product, $this->buyerToken),
                ]
            ],
    ];
});

dataset('create order online', function () {
    return [
        'valid data' =>
            [
                fn() => [
                    'request' => array_merge(
                        OrderHelper::getCreateOrderRequest($this->store),
                        ['paymentType'       => OrderHelper::PAYMENT_ONLINE]
                    ),
                    'mockData' => OrderHelper::getMockBasketData($this->store, $this->product),
                ]
            ],
    ];
});

dataset('create order invalid basket date', function () {
    return [
        'basket invalid date' =>
            [
                fn() => [
                    'request' => OrderHelper::getCreateOrderRequest($this->store),
                    'mockData' => array_merge(OrderHelper::getMockBasketData($this->store, $this->product),
                        [
                            'baskets' =>
                                [
                                    [
                                        'date' => OrderHelper::getDeliveryDate(2),
                                    ]
                                ],
                        ]
                    ),
                    'error' => [
                        "message" => sprintf(
                            '%s%s%s',
                            'Не найдена корзина для даты доставки - [',
                            OrderHelper::getDeliveryDate(),
                            ']'
                        )
                    ],
                ]
            ],
    ];
});

dataset('create order invalid types', function () {
    return [
        'invalid types' =>
            [
                fn() =>
                [
                    'request'               => OrderHelper::getInvalidTypesOrderRequest(),
                    'mockData'              => OrderHelper::getMockBasketData($this->store, $this->product),
                    'error'                 =>
                        [
                            'paymentType'       =>
                                [
                                    'validation.string',
                                    'validation.in',
                                ],
                            'source'            =>
                                [
                                    'validation.string',
                                    'validation.in',
                                ],
                            'comment'   => ['validation.string'],
                            'bindingId'   => ['validation.integer'],
                            'electronicChecks'  => ['validation.boolean'],
                            'delivery'  =>
                                [
                                    [
                                        'deliveryType'      =>
                                            [
                                                'validation.string',
                                                'validation.in'
                                            ],
                                        'deliverySubType'   =>
                                            [
                                                'validation.string',
                                                'validation.in'
                                            ],
                                        'deliveryDate'      =>
                                            [
                                                'validation.string',
                                                'validation.date_format',
                                                'validation.after'
                                            ],
                                        'deliveryTime'      => ['validation.string'],
                                        'storeOneCId'       => ['validation.string'],
                                        'cityId'            => ['validation.integer'],
                                        'address'           => ['validation.string'],
                                    ]
                                ],
                        ],
                ]
            ],
    ];
});

dataset('create order empty data', function () {
    return [
        'empty data' =>
            [
                fn() =>
                [
                    'request'               => [],
                    'mockData'              => OrderHelper::getMockBasketData($this->store, $this->product),
                    'error'                 =>
                        [
                            'delivery'      =>  ["validation.required"],
                            'paymentType'   =>  ["validation.required"],
                            'source'        =>  ["validation.required"],
                        ],
                ]
            ],
    ];
});

dataset('get orders pending', function () {
    return [
        'valid data' =>
            [
                fn() => [
                    'request' =>
                        [
                            'requestFrom'   =>  'online',
                            'state'         =>  'pending',
                        ],
                ]
            ],
    ];
});

dataset('get orders finished', function () {
    return [
        'valid data' =>
            [
                fn() => [
                    'request' =>
                        [
                            'requestFrom'   =>  'online',
                            'state'         =>  'finished',
                        ],
                ]
            ],
    ];
});



dataset('get orders empty data', function () {
    return [
        'empty data' =>
            [
                fn() => [
                    'request'   => [],
                    'error'     => [
                        'requestFrom'   =>  ["validation.required"]
                    ],
                ]
            ],
    ];
});

dataset('get orders invalid types', function () {
    return [
        'invalid types' =>
            [
                fn() => [
                    'request' =>
                        [
                            'requestFrom'   =>  2,
                            'state'         => 2,
                        ],
                    'error'     =>
                        [
                            'requestFrom'   =>  ["validation.in"],
                            'state'         =>  ["validation.in"],
                        ],
                ]
            ],
    ];
});

dataset('post orders review', function () {
    return [
        'valid' =>
            [
                fn() => [
                    'request' =>
                        [
                            'text'      => 'text of review',
                            'rating'    => 5,
                        ],
                ]
            ],
    ];
});

dataset('post orders review empty data', function () {
    return [
        'invalid types' =>
            [
                fn() => [
                    'request' => [],
                    'error'     =>
                        [
                            'rating'    =>  ["validation.required"],
                        ],
                ]
            ],
    ];
});

dataset('post orders review invalid', function () {
    return [
        'invalid types' =>
            [
                fn() => [
                    'request' =>
                        [
                            'text'      => 5,
                            'rating'    => 'text of review',
                        ],
                    'error'     =>
                        [
                            'text'      =>  ['validation.string'],
                            'rating'    =>  [
                                'validation.integer',
                                'validation.max.numeric'
                            ]
                        ],
                ]
            ],
    ];
});
