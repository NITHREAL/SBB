<?php

declare(strict_types=1);

namespace Domain\Audience\Models;

use Domain\Story\Models\Story;
use Domain\User\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Audience extends Model
{
    use AsSource;
    use Filterable;
    use HasFactory;

    protected $table = 'audiences';

    protected $casts = [
        'is_update'     => 'boolean',
        'filter_data'   => 'array',
    ];

    protected $fillable = [
        'title',
        'users_count',
        'is_update',
        'filter_data',
    ];

    protected $allowedSorts = [
        'id',
        'title',
        'created_at',
        'updated_at',
        'users_count'
    ];

    public function users(): BelongsToMany
    {
        return $this
            ->belongsToMany(
                User::class,
                'audience_list',
                'audience_id',
                'user_id'
            )
            ->withTimestamps();
    }

    public function stories(): HasMany
    {
        return $this->hasMany(Story::class);
    }
}
