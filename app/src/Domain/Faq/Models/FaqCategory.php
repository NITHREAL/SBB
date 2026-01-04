<?php

namespace Domain\Faq\Models;

use Domain\Faq\QueryBuilders\FaqCategoryQueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

/**
 * @method static FaqCategoryQueryBuilder query()
 */
class FaqCategory extends Model
{
    use AsSource;
    use Filterable;

    protected $fillable = [
        'title',
        'slug',
        'active',
        'sort',
        'protected'
    ];

    protected $casts = [
        'active'    => 'boolean',
        'sort'      => 'integer',
        'protected' => 'boolean',
    ];

    protected $allowedSorts = [
        'id',
        'active',
        'title',
        'sort'
    ];

    public function questions(): HasMany
    {
        return $this->hasMany(Faq::class);
    }

    public function newEloquentBuilder($query): FaqCategoryQueryBuilder
    {
        return new FaqCategoryQueryBuilder($query);
    }
}
