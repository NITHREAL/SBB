<?php

namespace Domain\Basket\Models;

use Database\Factories\BasketFactory;
use Domain\Basket\QueryBuilders\BasketQueryBuilder;
use Domain\Product\Models\Product;
use Domain\Promocode\Models\Promocode;
use Domain\User\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Orchid\Filters\Filterable as AdminFilterable;
use Orchid\Screen\AsSource;

/**
 * @method static BasketQueryBuilder query()
 */
class Basket extends Model
{
    use HasFactory;
    use AsSource;
    use AdminFilterable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'token',
        'delivery_params',
        'settings',
    ];

    protected $casts = [
        'delivery_params' => 'array',
        'settings' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)
            ->withPivot('count', 'from_order', 'weight');
    }

    public function promocode(): BelongsTo
    {
        return $this->belongsTo(Promocode::class, 'promo_id', 'id');
    }

    public function newEloquentBuilder($query): BasketQueryBuilder
    {
        return new BasketQueryBuilder($query);
    }

    protected static function newFactory(): Factory
    {
        return BasketFactory::new();
    }
}
