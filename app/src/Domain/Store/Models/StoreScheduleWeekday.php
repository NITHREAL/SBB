<?php

namespace Domain\Store\Models;

use Database\Factories\StoreScheduleWeekdayFactory;
use Domain\Order\Models\Delivery\PolygonType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StoreScheduleWeekday extends Model
{
    use HasFactory;

    protected $fillable = [
        'polygon_type_id',
        'week_day',
        'from',
        'to',
        'not_working'
    ];

    protected $casts = [
        'not_working' => 'boolean'
    ];

    public $timestamps = false;

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function polygonType(): BelongsTo
    {
        return $this->belongsTo(PolygonType::class);
    }

    protected static function newFactory(): Factory
    {
        return StoreScheduleWeekdayFactory::new();
    }
}
