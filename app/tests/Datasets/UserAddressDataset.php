<?php

namespace Tests\Datasets;

dataset('add user address valid', function () {
    return [
        'valid' =>
            [
                fn() => [
                    'request' => [
                        'cityId'                => $this->city->id,
                        'address'               => 'г Кемерово, ул Кемеровская 150',
                        'cityName'              => 'Кемерово',
                        'street'                => 'Кемеровская',
                        'house'                 => '150',
                        'building'              => 'а',
                        'entrance'              => 1,
                        'apartment'             => 222,
                        'floor'                 => 4,
                        'comment'               => 'comment for address',
                        'otherCustomer'         => true,
                        'otherCustomerPhone'    => '1111111111',
                        'otherCustomerName'     => 'Василий',
                    ],
                ]
            ],
    ];
});

dataset('update user address valid', function () {
    return [
        'valid' =>
            [
                fn() => [
                    'request' => [
                        'cityId'                => $this->city->id,
                        'address'               => 'г Новосибирск, ул Новосибирская 50',
                        'cityName'              => 'Новосибирск',
                        'street'                => 'Новосибирская',
                        'house'                 => '50',
                        'building'              => 'б',
                        'entrance'              => 2,
                        'apartment'             => 111,
                        'floor'                 => 2,
                        'comment'               => 'comment for address 2',
                        'otherCustomer'         => true,
                        'otherCustomerPhone'    => '2222222222',
                        'otherCustomerName'     => 'Валентин',
                    ],
                ]
            ],
    ];
});

dataset('user address empty data', function () {
    return [
        'empty data' =>
            [
                fn() => [
                    'request' => [],
                    'error' => '{"error":"validation.required; validation.required; "}',
                ]
            ],
    ];
});

dataset('user address invalid', function () {
    return [
        'invalid' =>
            [
                fn() => [
                    'request' => [
                        'cityId'                => $this->city->id,
                        'address'               => 0,
                        'cityName'              => 0,
                        'street'                => 0,
                        'house'                 => 0,
                        'building'              => 0,
                        'entrance'              => 'б',
                        'apartment'             => 'б',
                        'floor'                 => 'б',
                        'comment'               => 0,
                        'otherCustomer'         => 0,
                        'otherCustomerPhone'    => 0,
                        'otherCustomerName'     => 0,
                    ],
                    'error' => '{"error":"validation.string; validation.string; validation.string; validation.string; validation.string; validation.integer; validation.integer; validation.integer; validation.string; validation.string; validation.regex; validation.string; "}'],
            ],
    ];
});

dataset('already add user address', function () {
    return [
        'invalid' =>
            [
                fn() => [
                    'request' => [
                        'cityId'                => $this->city->id,
                        'address'               => 'г Кемерово, ул Кемеровская 150',
                        'cityName'              => 'Кемерово',
                        'street'                => 'Кемеровская',
                        'house'                 => '150',
                        'building'              => 'а',
                        'entrance'              => 1,
                        'apartment'             => 222,
                        'floor'                 => 4,
                        'comment'               => 'comment for address',
                        'otherCustomer'         => true,
                        'otherCustomerPhone'    => '1111111111',
                        'otherCustomerName'     => 'Василий',
                    ],
                    'error' => [
                        'error' => 'Такой адрес уже добавлен',
                    ],
                ],
            ],
    ];
});


