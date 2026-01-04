<?php

namespace Domain\User\Models\Category;

use Domain\FavoriteCategory\Models\FavoriteCategory;
use Domain\User\Models\User;
use Domain\User\QueryBuilders\UserFavoriteCategoryQueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static UserFavoriteCategoryQueryBuilder query()
 */
class UserFavoriteCategory extends Model
{
    protected $table = 'user_favorite_categories';

    protected $fillable = [
        'period',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(FavoriteCategory::class, 'category_id');
    }

    public function newEloquentBuilder($query): UserFavoriteCategoryQueryBuilder
    {
        return new UserFavoriteCategoryQueryBuilder($query);
    }
}
