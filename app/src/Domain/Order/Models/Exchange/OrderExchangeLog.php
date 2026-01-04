<?php

namespace Domain\Order\Models\Exchange;

use Domain\Order\Models\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Orchid\Screen\AsSource;

class OrderExchangeLog extends Model
{
    use AsSource;

    protected $table = 'order_exchanges';

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'type',
        'status',
        'data'
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
