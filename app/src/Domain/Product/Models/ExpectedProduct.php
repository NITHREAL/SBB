<?php

namespace Domain\Product\Models;

use Domain\User\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Orchid\Filters\Filterable as AdminFilterable;
use Orchid\Screen\AsSource;

class ExpectedProduct extends Model
{
    use AsSource;
    use AdminFilterable;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
