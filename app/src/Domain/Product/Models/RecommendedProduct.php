<?php

namespace Domain\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecommendedProduct extends Model
{
    protected $fillable = [
        'product_id',
        'sort',
    ];

    protected $casts = [
        'product_id'    => 'integer',
        'sort'          => 'string',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
