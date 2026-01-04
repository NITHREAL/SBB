<?php

namespace Domain\Order\Models;

use Domain\Product\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderProduct extends Model
{
    protected $table = 'order_product';

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_id',
        'product_system_id',
        'replacement_system_id',
        'unit_system_id',
        'status',
        'price',
        'price_discount',
        'price_promo',
        'price_buy',
        'original_quantity',
        'collected_quantity',
        'count',
        'weight',
        'total',
        'total_without_discount'
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_system_id', 'system_id');
    }
}
