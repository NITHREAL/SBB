<?php

namespace Domain\Promocode\Models;

use Domain\Promocode\QueryBuilders\PromocodeUsedPhoneQueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static PromocodeUsedPhoneQueryBuilder query()
 */
class PromocodeUsedPhone extends Model
{
    protected $table = 'promo_used_phones';

    protected $fillable = [
        'phone',
    ];

    public function promo(): HasMany
    {
        return $this->hasMany(Promocode::class);
    }

    public function newEloquentBuilder($query): PromocodeUsedPhoneQueryBuilder
    {
        return new PromocodeUsedPhoneQueryBuilder($query);
    }
}
