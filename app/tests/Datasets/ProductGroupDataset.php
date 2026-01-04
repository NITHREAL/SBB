<?php

dataset('get group product with filters', function () {
    return [
        'valid data' =>
            [
                fn() => [
                    'request' => [
                        'filter' => [
                            'available_today' => true,
                            'for_vegan' => true,
                        ],
                        'store_system_id' => $this->store->system_id,
                    ],
                ],
            ]
    ];
});

dataset('get group product with filters vegan false', function () {
    return [
        'valid data' =>
            [
                fn() => [
                    'request' => [
                        'filter' => [
                            'available_today' => true,
                            'for_vegan' => 0,
                        ],
                        'store_system_id' => $this->store->system_id,
                    ],
                ],
            ]
    ];
});

dataset('get group product with filters invalid', function () {
    return [
        'invalid data' =>
            [
                fn() => [
                    'request' => [
                        'filter' => [
                            'available_today' => 'available_today',
                            'for_vegan' => 'for_vegan',
                        ],
                        'store_system_id' => $this->store->system_id,
                    ],
                    'error'                 =>
                        [
                            'filter.available_today'      =>  ["validation.boolean"],
                            'filter.for_vegan'   =>  ["validation.boolean"],
                        ],
                ],
            ]
    ];
});
