<?php

namespace Domain\Store\Models;

use Domain\Product\Models\Product;
use Domain\Store\Accessors\DeliverySchedule;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Orchid\Screen\AsSource;

class ProductStore extends Model
{
    use AsSource;

    public $incrementing = false;

    public $timestamps = false;

    protected $table = 'product_store';

    protected $primaryKey = 'hash';

    protected $keyType = 'string';

    protected $casts = [
        'active'            => 'boolean',
        'delivery_schedule' => 'json',
        'price'             => 'float',
        'price_discount'    => 'float',
    ];

    protected $fillable = [
        'hash',
        'product_system_id',
        'store_system_id',
        'active',
        'price',
        'price_discount',
        'discount_expires_in',
        'count',
        'delivery_schedule',
    ];

    protected $dates = [
        'discount_expires_in'
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_system_id', 'system_id');
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_system_id', 'system_id');
    }

    public function preparedDeliverySchedule(): Attribute
    {
        return Attribute::get(new DeliverySchedule($this));
    }

    public static function makeHash($product1cId, $store1cId): string
    {
        return md5($store1cId . $product1cId);
    }
}
