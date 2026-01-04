<?php

namespace Domain\Product\Models;

use Database\Factories\ProductFactory;
use Domain\Image\Traits\Attachable;
use Domain\MetaTag\Traits\MetaTagValuable;
use Domain\Product\Helpers\DeliveryDateHelper;
use Domain\Product\QueryBuilders\ProductQueryBuilder;
use Domain\Store\Models\ProductStore;
use Domain\Unit\Models\Unit;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Infrastructure\Eloquent\Traits\Active;
use Orchid\Filters\Filterable as AdminFilterable;
use Orchid\Screen\AsSource;

/**
 * @method static ProductQueryBuilder query()
 */
class Product extends Model
{
    use HasFactory;
    use MetaTagValuable;
    use Active;
    use Attachable;
    use AsSource;
    use AdminFilterable;
    use Sluggable;

    protected $casts = [
        'active'                => 'boolean',
        'favorited'             => 'boolean',
        'delivery_in_country'   => 'boolean',
        'by_preorder'           => 'boolean',
        'show_as_preorder'      => 'boolean',
        'delivery_dates'        => 'json',
        'cooking'               => 'boolean',
        'proteins'              => 'float',
        'fats'                  => 'float',
        'carbohydrates'         => 'float',
        'is_weight'             => 'boolean',
        'weight'                => 'float',
        'popular'               => 'int',
        'barcodes'              => 'array',
        'is_ready_to_eat'       => 'boolean',
    ];

    protected $fillable = [
        'system_id',
        'unit_system_id',
        'active',
        'sku',
        'title',
        'slug',
        'description',
        'composition',
        'storage_conditions',
        'proteins',
        'fats',
        'carbohydrates',
        'nutrition_kcal',
        'nutrition_kj',
        'is_weight',
        'weight',
        'shelf_life',
        'delivery_in_country',
        'by_preorder',
        'show_as_preorder',
        'delivery_dates',
        'cooking',
        'sort',
        'by_points',
        'vegan',
        'sku_1c_ut',
        'rating',
        'popular',
        'farmer_system_id',
        'barcodes',
        'is_ready_to_eat',
    ];

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(
            Category::class,
            'category_product',
            'product_system_id',
            'category_system_id',
            'system_id',
            'system_id'
        );
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function getReviewedAttribute(): bool
    {
        return $this->reviews()->where('user_id', Auth::id())->exists();
    }

    public function relatedProducts(): BelongsToMany
    {
        return $this
            ->belongsToMany(
                self::class,
                'related_products',
                'main_product_id',
                'related_product_id'
            )
            ->withPivot(['sort']);
    }

    public function deliveryDates(): HasMany
    {
        return $this->hasMany(ProductDeliveryDate::class);
    }

    public function leftovers(): HasMany
    {
        return $this->hasMany(
            ProductStore::class,
            'product_system_id',
            'system_id',
        );
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unit_system_id', 'system_id');
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title',
                'unique' => true
            ]
        ];
    }


    public function newEloquentBuilder($query): ProductQueryBuilder
    {
        return new ProductQueryBuilder($query);
    }

    public function getNearestDeliveryDatesAttribute(): array
    {
        $deliveryDates = $this->deliveryDates()->pluck('date')->toArray();

        return DeliveryDateHelper::getNearestDateDelivery($deliveryDates);
    }

    protected static function newFactory(): Factory
    {
        return ProductFactory::new();
    }
}
