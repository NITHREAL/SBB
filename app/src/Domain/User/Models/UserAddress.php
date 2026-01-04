<?php

namespace Domain\User\Models;

use Database\Factories\UserAddressFactory;
use Domain\City\Models\City;
use Domain\User\QueryBuilders\UserAddressQueryBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 *@method static UserAddressQueryBuilder query()
 */
class UserAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'address',
        'city_name',
        'street',
        'house',
        'building',
        'entrance',
        'intercom',
        'apartment',
        'floor',
        'comment',
        'other_customer',
        'other_customer_phone',
        'other_customer_name',
        'has_not_intercom',
        'entrance_variant',
        'chosen',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'other_customer'    => 'boolean',
        'chosen'            => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function newEloquentBuilder($query): UserAddressQueryBuilder
    {
        return new UserAddressQueryBuilder($query);
    }

    public static function isUserAddressExists(
        int $userId,
        string $address,
        array $exceptedIds = [],
    ): bool {
        return self::query()
            ->whereUser($userId)
            ->where('address', $address)
            ->when(count($exceptedIds), function (Builder $query) use ($exceptedIds) {
                return $query->whereNotIn('id', $exceptedIds);
            })
            ->exists();
    }

    protected static function newFactory(): Factory
    {
        return UserAddressFactory::new();
    }
}
