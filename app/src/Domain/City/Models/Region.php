<?php

namespace Domain\City\Models;

use Database\Factories\RegionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Orchid\Filters\Filterable as AdminFilterable;
use Orchid\Screen\AsSource;
use Illuminate\Database\Eloquent\Factories\Factory;

class Region extends Model
{
    use HasFactory;
    use AdminFilterable;
    use AsSource;

    protected $fillable = [
        'system_id',
        'fias_id',
        'title',
        'sort',
    ];

    protected $allowedSorts = [
        'id',
        'title',
        'sort',
    ];

    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
    }

    protected static function newFactory(): Factory
    {
        return RegionFactory::new();
    }
}
