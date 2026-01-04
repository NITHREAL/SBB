<?php

declare(strict_types=1);

namespace Domain\ProductGroup\Models;

use Database\Factories\ProductGroupFactory;
use Domain\Audience\Models\Audience;
use Domain\Image\Models\Attachment;
use Domain\Image\Traits\Attachable;
use Domain\Product\Models\Product;
use Domain\ProductGroup\QueryBuilders\ProductGroupQueryBuilder;
use Domain\Story\Models\Story;
use Domain\Tag\Models\Tag;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Infrastructure\Eloquent\Traits\Active;
use Orchid\Filters\Filterable as AdminFilterable;
use Orchid\Screen\AsSource;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @method static ProductGroupQueryBuilder query()
 */
class ProductGroup extends Model
{
    use HasFactory;
    use AdminFilterable;
    use AsSource;
    use Attachable;
    use HasFactory;
    use Active;

    protected $table = 'groups';

    protected $casts = [
        'active' => 'boolean'
    ];

    protected $fillable = [
        'active',
        'title',
        'slug',
        'sort',
        'site',
        'mobile',
    ];

    protected $allowedSorts = [
        'id',
        'active',
        'title',
        'slug',
        'sort',
        'site',
        'mobile',
        'products_count',
    ];

    public $timestamps = false;

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(
            Product::class,
            'group_products',
            'group_id',
            'product_id',
        );
    }

    public function audience(): BelongsTo
    {
        return $this->belongsTo(Audience::class);
    }

    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function backgroundImage(): BelongsTo
    {
        return $this->belongsTo(
            Attachment::class,
            'background_image_id',
            'id',
        );
    }

    public function story(): BelongsTo
    {
        return $this->belongsTo(Story::class);
    }

    public function newEloquentBuilder($query): ProductGroupQueryBuilder
    {
        return new ProductGroupQueryBuilder($query);
    }

    protected static function newFactory(): Factory
    {
        return ProductGroupFactory::new();
    }
}
