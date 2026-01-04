<?php

return [
    // UserAddress
    'invalid_user_for_address_record'       => 'Вы пытаетесь изменить адрес другого пользователя',
    'delete_user_address_failed'            => 'Во время удаления адреса произошла ошибка',
    // Order
    'payment_online_unavailable'            => 'Payment by online is unavailable',
    'pickup_delivery_store_not_selected'    => 'Pickup delivery store is not selected',
    'phone_number_not_found'                => 'Phone number is missing',
    'payment_amount_invalid'                => 'Invalid value of the payment amount [:amount]',

    // Notifications
    'notifications' => [
        'order_bonus_scores'    => [
            'title'              => ':sign:scores балла',
            'message'            => 'Начисление за покупку от :date'
        ],
        'category_coupon'       => [
            'title'             => '-:scores баллов',
            'message'           => 'Обмен баллов на :name',
        ],
    ],

    // Sbermarket
    'sbermarket' => [
        'order.unknown_event_type'    => 'Неизвестный event type',
        'order_status_change_error'   => 'Ошибка при изменении статуса заказа на [:status]. :message',
        'order_update_error'          => 'Ошибка при обновлении заказа из сбермаркета. :message',
        'order_create_error'          => 'Ошибка при создании заказа из сбермаркета. :message',
    ],
];
