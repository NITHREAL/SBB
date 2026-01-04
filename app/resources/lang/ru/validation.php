<?php

return [
    'signature' => 'Код неверен',

    'user_address'   => [
        'address'   => [
            'unique'    => 'Такой адрес уже добавлен',
        ]
    ],
    'captcha' => 'The :attribute field is not a valid reCAPTCHA',

    'Number is required' => 'Номер обязателен',
    'Number must be an integer' => 'Номер должен быть целым числом',
    'Title is required' => 'Название обязательно',
    'Title must be a string' => 'Название должно быть строкой',
    'Title may not be greater than 255 characters' => 'Название не должно превышать 255 символов',
    'Minimum bonus points are required' => 'Минимальное количество бонусов обязательно',
    'Minimum bonus points must be an integer' => 'Минимальное количество бонусов должно быть целым числом',
    'Minimum bonus points must be at least 0' => 'Минимальное количество бонусов не может быть отрицательным',
    'Maximum bonus points must be an integer' => 'Максимальное количество бонусов должно быть целым числом',
    'Maximum bonus points must be at least 0' => 'Максимальное количество бонусов не может быть отрицательным',
    'Maximum bonus points must be greater than minimum bonus points' => 'Максимальное количество бонусов должно быть больше минимального количества бонусов',

    'group' => [
        'products' => [
            'product_required' => 'Укажите товар или удалите строку.',
        ]
    ],

    'store' => [
        'contacts' => [
            'phone_or_email' => 'Не верный формат номера телефона или email',
            ]
        ],

    'only_cyrillic_symbols' => 'Поле принимает только символы кириллицы',

    'basket' => [
        'set_count' => [
            'weight_or_count_message' => 'Укажите количество товара или его вес',
        ]
    ]
];
