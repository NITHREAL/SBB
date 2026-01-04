<?php

namespace Domain\Farmer\Models;

use Database\Factories\FarmerFactory;
use Domain\Farmer\Accessors\FarmerReviewInfo;
use Domain\Farmer\QueryBuilders\FarmerQueryBuilder;
use Domain\Image\Traits\Attachable;
use Domain\MetaTag\Traits\MetaTagValuable;
use Domain\Product\Models\Product;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Infrastructure\Eloquent\Traits\Active;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Farmer extends Model
{
    use HasFactory;
    use Active;
    use AsSource;
    use Attachable;
    use Filterable;
    use MetaTagValuable;

    protected $fillable = [
        'system_id',
        'active',
        'name',
        'supply_description',
        'description',
        'sort',
        'slug',
        'address',
    ];

    protected $casts = [
        'review_info' => 'array',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'farmer_system_id', 'system_id');
    }

    public function newEloquentBuilder($query): FarmerQueryBuilder
    {
        return new FarmerQueryBuilder($query);
    }

    public function reviewInfoFormat(): Attribute
    {
        return Attribute::get(new FarmerReviewInfo($this));
    }

    protected static function newFactory(): Factory
    {
        return FarmerFactory::new();
    }
}
