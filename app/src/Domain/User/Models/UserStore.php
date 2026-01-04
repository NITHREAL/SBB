<?php

namespace Domain\User\Models;

use Domain\Store\Models\Store;
use Domain\User\QueryBuilders\UserStoreQueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static UserStoreQueryBuilder query()
 */
class UserStore extends Model
{
    protected $table = 'favorite_stores';

    protected $fillable = [
        'chosen',
    ];

    protected $casts = [
        'chosen' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function newEloquentBuilder($query): UserStoreQueryBuilder
    {
        return new UserStoreQueryBuilder($query);
    }
}
