<?php

namespace Domain\Store\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Database\Factories\StoreFactory;
use Domain\City\Models\City;
use Domain\MetaTag\Traits\MetaTagValuable;
use Domain\Order\Models\Delivery\Polygon;
use Domain\Order\Models\Payment\PaymentType;
use Domain\Product\Models\Product;
use Domain\Store\Accessors\IsOpened;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Domain\Store\QueryBuilders\StoreQueryBuilder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Infrastructure\Eloquent\Traits\Active;
use Orchid\Filters\Filterable as AdminFilterable;
use Orchid\Screen\AsSource;

/**
 * @method static StoreQueryBuilder query()
 */
class Store extends Model
{
    use HasFactory;
    use Active;
    use AdminFilterable;
    use AsSource;
    use MetaTagValuable;
    use Sluggable;

    protected $casts = [
        'active'                => 'boolean',
        'payments_from_city'    => 'boolean',
        'is_dark_store'         => 'boolean'
    ];

    protected $fillable = [
        'set_id',
        'system_id',
        'legal_entity_1c_id',
        'active',
        'title',
        'address',
        'work_time',
        'latitude',
        'longitude',
        'sort',
        'slug',
        'payments_from_city',
        'is_dark_store',
        'city_system_id',
    ];

    protected $allowedSorts = [
        'id',
        'active',
        'title',
        'is_dark_store',
        'sort',
    ];

    public function leftovers(): HasMany
    {
        return $this->hasMany(ProductStore::class, 'store_system_id', 'system_id');
    }

    public function products(): BelongsToMany
    {
        return $this
            ->belongsToMany(
                Product::class,
                'product_store',
                'store_system_id',
                'product_system_id',
                'system_id',
                'system_id'
            )
            ->withPivot(['active', 'price', 'price_discount', 'count', 'delivery_schedule']);
    }

    public function isOpened(): Attribute
    {
        return Attribute::get(new IsOpened($this));
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function polygons(): HasMany
    {
        return $this
            ->hasMany(Polygon::class, 'store_system_id', 'system_id')
            ->orderBy('id');
    }

    public function scheduleWeekdays(): HasMany
    {
        return $this->hasMany(StoreScheduleWeekday::class);
    }

    public function scheduleDates(): HasMany
    {
        return $this->hasMany(StoreScheduleDate::class);
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(StoreContact::class)->orderBy('type');
    }

    public function payments(): BelongsToMany
    {
        return $this->belongsToMany(
            PaymentType::class,
            'payment_store',
            'store_id',
            'payment_id'
        );
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

    public function newEloquentBuilder($query): StoreQueryBuilder
    {
        return new StoreQueryBuilder($query);
    }

    protected static function newFactory(): Factory
    {
        return StoreFactory::new();
    }
}
