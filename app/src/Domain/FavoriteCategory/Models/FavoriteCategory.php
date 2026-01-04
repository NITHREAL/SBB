<?php

namespace Domain\FavoriteCategory\Models;

use Domain\FavoriteCategory\QueryBuilders\FavoriteCategoryQueryBuilder;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static FavoriteCategoryQueryBuilder query()
 */
class FavoriteCategory extends Model
{
    protected $table = 'favorite_categories';

    protected $fillable = [
        'title',
        'loyalty_id',
        'period',
        'image',
    ];

    public function newEloquentBuilder($query): FavoriteCategoryQueryBuilder
    {
        return new FavoriteCategoryQueryBuilder($query);
    }
}
