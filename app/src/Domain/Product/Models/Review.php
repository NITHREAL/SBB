<?php

namespace Domain\Product\Models;

use Carbon\Carbon;
use Database\Factories\ReviewFactory;
use Domain\Image\Traits\Attachable;
use Domain\Product\QueryBuilders\ReviewQueryBuilder;
use Domain\User\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Infrastructure\Eloquent\Traits\Active;
use Orchid\Filters\Filterable as AdminFilterable;
use Orchid\Screen\AsSource;

/**
 * @method static ReviewQueryBuilder query()
 */
class Review extends Model
{
    use HasFactory;
    use Active;
    use AdminFilterable;
    use AsSource;
    use Attachable;
    use HasFactory;
    use SoftDeletes;

    protected $casts = [
        'active' => 'boolean',
        'archived' => 'boolean'
    ];

    protected $fillable = [
        'product_id',
        'user_id',
        'active',
        'archived',
        'rating',
        'text',
        'user_name',
        'user_phone'
    ];

    protected $allowedSorts = [
        'id',
        'product_id',
        'active',
        'rating',
        'text',
        'created_at',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getCreatedAtAttribute($value)
    {
        $carbonDate = Carbon::parse($value)->locale('ru');

        return $carbonDate->isoFormat('DD MMM YYYY', 'Do MMM YYYY');
    }

    public function newEloquentBuilder($query): ReviewQueryBuilder
    {
        return new ReviewQueryBuilder($query);
    }

    protected static function newFactory(): Factory
    {
        return ReviewFactory::new();
    }
}
