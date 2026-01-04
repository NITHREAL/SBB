<?php

namespace Domain\Product\Models;

use Domain\Product\QueryBuilders\PopularProductQueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static PopularProductQueryBuilder query()
 */
class PopularProduct extends Model
{
    protected $fillable = [
        'product_id',
        'sort',
    ];

    protected $casts = [
        'product_id'    => 'integer',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function newEloquentBuilder($query): PopularProductQueryBuilder
    {
        return new PopularProductQueryBuilder($query);
    }
}
