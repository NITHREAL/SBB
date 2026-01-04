<?php

namespace Domain\Store\Models;

use Domain\Image\Traits\Attachable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Orchid\Filters\Filterable as AdminFilterable;
use Orchid\Screen\AsSource;

class AnalyticActivityStore extends Model
{
    use AsSource;
    use Attachable;
    use AdminFilterable;

    protected $fillable = [
        'store_id',
        'title',
        'number_sales',
        'sum_sales',
        'average_check',
        'accrued_points',
        'deducted_points',
        'amount_gifts',
        'new_users',
        'date_activity',
    ];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }
}
