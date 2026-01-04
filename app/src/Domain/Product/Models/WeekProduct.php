<?php

namespace Domain\Product\Models;

use Domain\Product\QueryBuilders\PopularProductQueryBuilder;
use Domain\Product\QueryBuilders\WeekProductQueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static PopularProductQueryBuilder query()
 */
class WeekProduct extends Model
{
    protected $fillable = [
        'product_id',
        'sort',
    ];

    protected $casts = [
        'product_id'    => 'integer',
        'sort'          => 'integer',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function newEloquentBuilder($query): WeekProductQueryBuilder
    {
        return new WeekProductQueryBuilder($query);
    }
}
