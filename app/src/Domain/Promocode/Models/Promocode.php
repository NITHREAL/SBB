<?php

namespace Domain\Promocode\Models;

use Database\Factories\PromocodeFactory;
use Domain\Order\Models\Order;
use Domain\Product\Models\Category;
use Domain\Product\Models\Product;
use Domain\ProductGroup\Models\ProductGroup;
use Domain\Promocode\Models\Accessors\TotalDiscount;
use Domain\Promocode\Models\Accessors\UsedCount;
use Domain\Promocode\QueryBuilders\PromocodeQueryBuilder;
use Domain\User\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Infrastructure\Eloquent\Traits\Active;
use Orchid\Filters\Filterable;
use Orchid\Filters\Filterable as AdminFilterable;
use Orchid\Screen\AsSource;

/**
 *@method static PromocodeQueryBuilder query()
 */
class Promocode extends Model
{
    use HasFactory;
    use Active;
    use AsSource;
    use AdminFilterable;
    use Filterable;

    protected $table = 'promos';

    protected $casts = [
        'active'            => 'boolean',
        'percentage'        => 'boolean',
        'any_user'          => 'boolean',
        'free_delivery'     => 'boolean',
        'one_use_per_phone' => 'boolean',
        'only_one_use'      => 'boolean',
        'use_excluded'      => 'boolean'
    ];

    protected $hidden = [
        'updated_at',
        'created_at',
        'deleted_at',
        'free_delivery',
        'order_type',
        'delivery_type',
        'any_user',
        'one_use_per_phone',
        'expires_in',
        'discount',
        'min_amount',
        'limit',
        'percentage',
        'any_product',
        'mobile',
        'active',
        'only_one_use',
        'use_excluded'
    ];

    protected $fillable = [
        'active',
        'code',
        'discount',
        'percentage',
        'limit',
        'min_amount',
        'order_type',
        'delivery_type',
        'any_user',
        'any_product',
        'expires_in',
        'created_at',
        'updated_at',
        'deleted_at',
        'free_delivery',
        'mobile',
        'title',
        'description',
        'one_use_per_phone',
        'show_audience_id',
        'only_one_use',
        'use_excluded'
    ];

    protected $allowedSorts = [
        'id',
        'active',
        'code',
        'discount',
        'percentage',
        'limit',
        'min_amount',
        'order_type',
        'delivery_type',
        'any_user',
        'any_product',
        'expires_in',
        'created_at',
        'updated_at',
        'deleted_at',
        'free_delivery',
        'mobile',
        'title',
        'description',
        'one_use_per_phone',
        'show_audience_id',
        'only_one_use',
        'use_excluded'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($promo) {
            //TODO добавить логику этого метода в сервис
            //$promo->generateUniqueCode();
        });
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(
            Category::class,
            'promo_category',
            'promo_id',
            'category_id',
            'id',
            'id'
        );
    }

    public function excludedCategories(): BelongsToMany
    {
        return $this->belongsToMany(
            Category::class,
            'promo_excluded_category',
            'promo_id',
            'category_id',
            'id',
            'id'
        );
    }

    public function excludedGroups(): BelongsToMany
    {
        return $this->belongsToMany(
            ProductGroup::class,
            'promo_excluded_group',
            'promo_id',
            'group_id',
            'id',
            'id'
        );
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(
            Product::class,
            'promo_product',
            'promo_id',
            'product_id'
        );
    }

    public function excludedProducts(): BelongsToMany
    {
        return $this->belongsToMany(
            Product::class,
            'promo_excluded_product',
            'promo_id',
            'product_id'
        );
    }

    public function users(): BelongsToMany
    {
        return $this
            ->belongsToMany(
                User::class,
                'promo_user',
                'promo_id',
                'user_id'
            )
            ->withPivot('max_uses');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'promo_id');
    }

    public function usedCount(): Attribute
    {
        return Attribute::get(new UsedCount($this));
    }

    public function totalDiscount(): Attribute
    {
        return Attribute::get(new TotalDiscount($this));
    }

    public function newEloquentBuilder($query): PromocodeQueryBuilder
    {
        return new PromocodeQueryBuilder($query);
    }

    protected static function newFactory(): Factory
    {
        return PromocodeFactory::new();
    }
}
