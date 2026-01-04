<?php

namespace Domain\Tag\Models;

use Domain\ProductGroup\Models\ProductGroup;
use Domain\Tag\QueryBuilders\TagQueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Orchid\Filters\Filterable as AdminFilterable;
use Orchid\Screen\AsSource;

/**
 * @method static TagQueryBuilder query()
 */
class Tag extends Model
{
    use AdminFilterable;
    use AsSource;

    public $fillable = [
        'text',
        'color',
        'active',
        'show_forced',
    ];

    public $casts = [
        'text'          => 'string',
        'color'         => 'string',
        'active'        => 'boolean',
        'show_forced'   => 'boolean',
    ];

    protected $allowedSorts = [
        'id',
        'text',
        'active',
    ];

    public function group(): MorphToMany
    {
        return $this->morphedByMany(ProductGroup::class, 'taggable');
    }

    public function newEloquentBuilder($query): TagQueryBuilder
    {
        return new TagQueryBuilder($query);
    }
}
