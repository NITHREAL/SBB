<?php

namespace Domain\Faq\Models;

use Domain\Faq\QueryBuilders\FaqQueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

/**
 * @method static FaqQueryBuilder query()
 */
class Faq extends Model
{
    use AsSource;
    use Filterable;

    protected $table = 'faq';

    protected $fillable = [
        'title',
        'text',
        'slug',
        'active',
        'sort',
    ];

    protected $casts = [
        'active'    => 'boolean',
        'sort'      => 'integer',
    ];

    protected $allowedSorts = [
        'id',
        'title',
        'sort',
        'active',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(FaqCategory::class, 'faq_category_id');
    }

    public function newEloquentBuilder($query): FaqQueryBuilder
    {
        return new FaqQueryBuilder($query);
    }
}
