<?php

namespace Domain\Order\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderReview extends Model
{
    protected $fillable = [
        'text',
        'rate',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
