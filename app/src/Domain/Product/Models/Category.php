<?php

namespace Domain\Product\Models;

use Domain\Image\Traits\Attachable;
use Domain\MetaTag\Traits\MetaTagValuable;
use Domain\Product\QueryBuilders\CategoryQueryBuilder;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Infrastructure\Eloquent\Models\NestedModel;
use Infrastructure\Eloquent\Traits\Active;
use Orchid\Filters\Filterable as AdminFilterable;
use Orchid\Screen\AsSource;

/**
 * @method static CategoryQueryBuilder query()
 */
class Category extends NestedModel
{
    use Active;
    use Attachable;
    use MetaTagValuable;
    use AsSource;
    use AdminFilterable;
    use Sluggable;
    use Attachable;

    protected $casts = [
        'active'                => 'boolean',
        'children_system_ids'   => 'array',
    ];

    protected $fillable = [
        'active',
        'system_id',
        'parent_system_id',
        'title',
        'slug',
        'sort',
        'special_type',
    ];

    protected $allowedSorts = [
        'id',
        'active',
        'title',
        'sort',
    ];

    protected $primaryKey = 'system_id';

    protected $keyType = 'string';

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(
            Product::class,
            'category_product',
            'category_system_id',
            'product_system_id',
            'system_id',
            'system_id'
        );
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_system_id');
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

    public function newEloquentBuilder($query): CategoryQueryBuilder
    {
        return new CategoryQueryBuilder($query);
    }
}
