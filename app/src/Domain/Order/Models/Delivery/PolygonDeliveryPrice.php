<?php

namespace Domain\Order\Models\Delivery;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PolygonDeliveryPrice extends Model
{
    protected $table = 'delivery_prices_in_polygons';

    protected $fillable = [
        'polygon_id',
        'from',
        'to',
        'price',
    ];

    protected $casts = [
        'from' => 'float',
        'to' => 'float',
        'price' => 'float',
    ];

    public function polygon(): BelongsTo
    {
        return $this->belongsTo(Polygon::class);
    }
}
