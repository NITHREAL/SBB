<?php

namespace Domain\Store\Models;

use Domain\Order\Models\Delivery\PolygonType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StoreScheduleDate extends Model
{
    protected $fillable = [
        'polygon_type_id',
        'date',
        'from',
        'to',
        'not_working'
    ];

    protected $casts = [
        'not_working' => 'boolean'
    ];

    public $timestamps = false;

    public function polygonType(): BelongsTo
    {
        return $this->belongsTo(PolygonType::class);
    }
}
