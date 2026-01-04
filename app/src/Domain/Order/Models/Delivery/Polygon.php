<?php

namespace Domain\Order\Models\Delivery;

use Database\Factories\PolygonFactory;
use Domain\Store\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Polygon extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_system_id',
        'coordinates',
        'stroke_color',
        'fill_color',
        'type',
    ];

    protected $casts = [
        'coordinates' => 'array',
    ];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_system_id', 'system_id');
    }

    public function deliveryPrices(): HasMany
    {
        return $this->hasMany(PolygonDeliveryPrice::class);
    }

    public function getDeliveryPriceForFree(): ?object
    {
        return $this->deliveryPrices()
            ->orderByDesc('updated_at')
            ->where('price', '=', 0.00)
            ->first();
    }

    protected static function newFactory(): Factory
    {
        return PolygonFactory::new();
    }
}
