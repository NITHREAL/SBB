<?php

namespace Domain\Order\Models\Exchange;

use Domain\Order\Models\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderExchangeRequestLog extends Model
{
    protected $table = 'order_exchange_requests';

    protected $fillable = [
        'system_id',
        'status',
        'data',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'system_id', 'system_id');
    }
}
