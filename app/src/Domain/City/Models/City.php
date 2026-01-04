<?php

namespace Domain\City\Models;

use Database\Factories\CityFactory;
use Domain\Store\Models\Store;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Orchid\Filters\Filterable as AdminFilterable;
use Orchid\Screen\AsSource;

class City extends Model
{
    use HasFactory;
    use AdminFilterable;
    use AsSource;

    protected $fillable = [
        'system_id',
        'region_system_id',
        'title',
        'fias_id',
        'latitude',
        'longitude',
        'is_settlement',
        'timezone',
        'sort',
    ];

    protected $casts = [
        'is_settlement' => 'boolean',
    ];

    protected $allowedSorts = [
        'id',
        'title',
        'sort',
    ];

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function stores(): HasMany
    {
        return $this->hasMany(Store::class)->orderBy('sort');
    }

    public function included_settlements(): HasMany
    {
        return $this->hasMany(City::class, 'for_city_id', 'id');
    }

    public function scopeWhereFiasId(Builder $query, string $fiasId): Builder
    {
        return $query->where('fias_id', $fiasId);
    }

    public static function getOneByFiasId(string $fiasId): ?self
    {
        return self::query()
            ->whereFiasId($fiasId)
            ->first();

    }

    protected static function newFactory(): Factory
    {
        return CityFactory::new();
    }
}
