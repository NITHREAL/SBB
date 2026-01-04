<?php

declare(strict_types=1);

namespace Domain\Story\Models;

use Domain\Audience\Models\Audience;
use Domain\Image\Models\Attachment;
use Domain\Image\Traits\Attachable;
use Domain\Story\QueryBuilders\StoryQueryBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Orchid\Filters\Filterable as AdminFilterable;
use Orchid\Screen\AsSource;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static StoryQueryBuilder query()
 */
class Story extends Model
{
    use Attachable;
    use HasFactory;
    use AsSource;
    use AdminFilterable;

    protected $casts = [
        'title'                 => 'string',
        'active'                => 'boolean',
        'auto_open'             => 'boolean',
        'available_in_groups'   => 'boolean'
    ];

    protected $fillable = [
        'id',
        'active',
        'title',
        'auto_open',
        'available_in_groups'
    ];

    protected $hidden = [
        'updated_at',
        'deleted_at',
    ];

    protected $allowedSorts = [
        'id',
        'title',
        'active',
        'created_at',
        'updated_at'
    ];

    public function pages(): HasMany
    {
        return $this->hasMany(StoryPage::class)->orderBy('position');
    }

    public function audience(): BelongsTo
    {
        return $this->belongsTo(Audience::class);
    }

    public function image(): BelongsTo
    {
        return $this->belongsTo(Attachment::class);
    }

    // Скоуп необходим для коректной работы поля "Закрепленная история" на странице редактирования подборки
    public function scopeAvailableInGroups(StoryQueryBuilder $query): StoryQueryBuilder
    {
        return $query->where('stories.available_in_groups', true);
    }

    public function newEloquentBuilder($query): StoryQueryBuilder
    {
        return new StoryQueryBuilder($query);
    }
}
