<?php

use Carbon\Carbon;
use Tests\Unit\Order\DeliveryType\DeliveryTypeHelper;

dataset('set delivery type valid', function () {
    return [
        'pickup' =>
            [
                fn() => [
                    'request' => DeliveryTypeHelper::getDefaultRequest($this->city, $this->store),
                ]
            ],
        'delivery' =>
            [
                fn() => [
                    'request' => array_merge(
                        DeliveryTypeHelper::getDefaultRequest($this->city, $this->store),
                        [
                            'deliveryType' => 'delivery',
                        ],
                    ),
                ]
            ],
        ];
});

dataset('set delivery type invalid pickup', function () {
    return [
        'invalid data' =>
            [
                fn() => [
                    'request' => [
                        "address" => DeliveryTypeHelper::DEFAULT_ADDRESS,
                        "deliveryType" => DeliveryTypeHelper::DELIVERY_TYPE_PICKUP,
                        "deliverySubType" => DeliveryTypeHelper::DELIVERY_SUB_TYPE,
                        'date' => Carbon::yesterday()->format('Y-m-d'),
                        "time" => DeliveryTypeHelper::DELIVERY_TIME,
                    ],
                    'error' => [
                        'date'          => ['validation.after'],
                        'cityId'        => ['validation.required'],
                        'storeId'       => ['validation.required_if'],
                    ],
                ]
            ],
        ];
});

dataset('set delivery type invalid delivery', function () {

    return [
        'invalid address' =>
            [
                fn() => [
                    'request' => array_merge(
                        DeliveryTypeHelper::getDefaultRequest($this->city, $this->store),
                        array_merge(
                            DeliveryTypeHelper::getDeliveryParams($this->city),
                            ['address'       => 'г Новосибирск, пр-кт Шахтеров, д 56',]
                        ),
                    ),
                    'error' => [
                        'message' => 'Доставка на выбранный адрес недоступна'
                    ],
                ]
            ],
        ];
});

dataset('set delivery type by city valid',function () {
    return [
        'delivery' =>
            [
                fn() => [
                    'request' => DeliveryTypeHelper::getDeliveryParams($this->city),
                ]
            ],
        'pickup' =>
            [
                fn() => [
                    'request' => DeliveryTypeHelper::getPickupParams($this->city)
                ]
            ],
        ];
});

dataset('set delivery type by city invalid',function () {
    return [
        'invalid types' =>
            [
                fn() => [
                    'request' => [
                        'cityId'        => 'cityId',
                        'deliveryType'  => 2,
                    ],
                    'error' => [
                        'cityId' => ['validation.integer'],
                        'deliveryType' => [
                            'validation.string',
                            'validation.in'
                        ],
                    ],
                ]
            ],
        ];
});

dataset('get available delivery type valid',function () {
    return [
        'pickup valid' =>
            [
                fn() => [
                    'request' => array_merge(
                        DeliveryTypeHelper::getPickupParams($this->city),
                        ['storeId'  => $this->store->id],
                    )
                ]
            ],
        'delivery valid' =>
            [
                fn() => [
                    'request' => array_merge(
                        DeliveryTypeHelper::getDeliveryParams($this->city),
                        ['address'  => DeliveryTypeHelper::DEFAULT_ADDRESS],
                    ),
                ]
            ],
        ];
});

dataset('get available delivery type pickup valid',function () {
    return [
        'pickup valid' =>
            [
                fn() => [
                    'request' => array_merge(
                        DeliveryTypeHelper::getPickupParams($this->city),
                        ['storeId'  => $this->store->id],
                    )
                ]
            ],
        ];
});

dataset('get available delivery type delivery valid',function () {
    return [
        'delivery valid' =>
            [
                fn() => [
                    'request' => array_merge(
                        DeliveryTypeHelper::getDeliveryParams($this->city),
                        ['address'  => $this->store->address],
                    ),
                ],
            ],
        ];
});

dataset('get available delivery type invalid',function () {
    return [
        'delivery invalid' =>
            [
                fn() => [
                    'request' => DeliveryTypeHelper::getDeliveryParams($this->city),
                    'error' => [
                        'address' => ['validation.required_if'],
                    ],
                ]
            ],
        'pickup invalid' =>
            [
                fn() => [
                    'request' => DeliveryTypeHelper::getPickupParams($this->city),
                    'error' => [
                        'storeId' => ['validation.required_if']
                    ],
                ]
            ],
        ];
});

dataset('get available delivery type invalid empty',function () {
    return [
        'empty data' =>
            [
                fn() => [
                    'request' => [],
                    'error' => [
                        'cityId'        => ['validation.exists'],
                        'deliveryType'  => ['validation.required'],
                    ],
                ]
            ],
        ];
});

dataset('get available delivery type wrong type',function () {
    return [
        'storeId invalid type' =>
            [
                fn() => [
                    'request' => array_merge(
                        DeliveryTypeHelper::getPickupParams($this->city),
                        ['storeId'  => 'store'],
                    ),
                    'error' => [
                        'storeId' => ['validation.integer']
                    ],
                ]
            ],

        ];
});
