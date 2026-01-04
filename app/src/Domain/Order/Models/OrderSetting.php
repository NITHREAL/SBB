<?php

namespace Domain\Order\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderSetting extends Model
{
    protected $fillable = [
        'unavailable_settings',
        'weight_settings',
        'order_for_other_person_settings',
        'other_person_phone',
        'other_person_name',
        'check_type',
        'order_id',
    ];

    public $timestamps = false;

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
