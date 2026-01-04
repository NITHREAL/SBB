<?php

dataset('get catalog with filters', function () {
    return [
        'valid data' =>
            [
                fn() => [
                    'request' => [
                        'filter' => [
                            'available_today' => 1,
                            'for_vegan' => 0,
                        ],
                    ],
                    'filter' => [
                        "filter" => [
                            "available_today" => "1",
                            "for_vegan" => "0"
                        ]
                    ]
                ],
            ]
    ];
});
