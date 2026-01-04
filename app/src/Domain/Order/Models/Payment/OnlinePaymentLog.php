<?php

namespace Domain\Order\Models\Payment;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class OnlinePaymentLog extends Model
{
    use AsSource;

    protected $fillable = [
        'online_payment_id',
        'method',
        'error_code',
        'error_message',
        'request',
        'response'
    ];

    public $timestamps = true;

    protected $casts = [
        'request'   => 'array',
        'response'  => 'array'
    ];
}
