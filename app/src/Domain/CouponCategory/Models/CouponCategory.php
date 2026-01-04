<?php

namespace Domain\CouponCategory\Models;

use Domain\Image\Models\Attachment;
use Domain\Image\Traits\Attachable;
use Domain\CouponCategory\QueryBuilders\CouponCategoryQueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;
use Infrastructure\Eloquent\Traits\Active;
use Infrastructure\Eloquent\Traits\CustomTableName;
use Orchid\Filters\Filterable as AdminFilterable;
use Orchid\Screen\AsSource;

/**
 *@method static CouponCategoryQueryBuilder query()
 */
class CouponCategory extends Model
{
    use Active;
    use CustomTableName;
    use Attachable;
    use AsSource;
    use AdminFilterable;

    protected $fillable = [
        'title',
        'description',
        'purchase_terms',
        'price',
        'sort',
        'active',
    ];

    protected $table = 'coupon_categories';

    protected $casts = [
        'guid'              => 'string',
        'price'             => 'integer',
        'title'             => 'string',
        'description'       => 'string',
        'purchase_terms'    => 'string',
        'sort'              => 'integer',
        'active'            => 'boolean'
    ];

    public function mainImage(): BelongsTo
    {
        return $this->belongsTo(Attachment::class, 'image_id');
    }

    public static function getAvailableCouponsForUserList(): Collection
    {
        return self::query()
            //->with(['images', 'backgroundImage']) TODO добавить после появления изображений орчида
            ->actual()
            ->orderBy('sort')
            ->get();
    }

    public static function getAvailableCouponsForBuy(): Collection
    {
        return self::query()
            //->with(['images', 'backgroundImage']) TODO добавить после появления изображений орчида
            ->active()
            ->actual()
            ->orderBy('sort')
            ->get();
    }

    public function newEloquentBuilder($query): CouponCategoryQueryBuilder
    {
        return new CouponCategoryQueryBuilder($query);
    }
}
