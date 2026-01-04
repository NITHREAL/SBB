<?php

declare(strict_types=1);

namespace Domain\BonusLevel\Models;

use Domain\BonusLevel\QueryBuilders\BonusLevelQueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

/**
 * @method static BonusLevelQueryBuilder query()
 */
class BonusLevel extends Model
{
    use AsSource;
    use Filterable;

    protected $fillable = [
        'loyalty_id',
        'number',
        'title',
        'description',
        'min_bonus_points',
        'max_bonus_points',
    ];

    protected $casts = [
        'min_bonus_points'  => 'integer',
        'max_bonus_points'  => 'integer',
    ];

    public function newEloquentBuilder($query): BonusLevelQueryBuilder
    {
        return new BonusLevelQueryBuilder($query);
    }
}
