<?php

namespace Infrastructure\Setting\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Infrastructure\Setting\Builder\SettingQueryBuilder;
use Orchid\Filters\Filterable;
use Orchid\Metrics\Chartable;
use Orchid\Screen\AsSource;

class Setting extends Model
{
    use AsSource;
    use HasFactory;
    use Chartable;
    use Filterable;

    protected $casts = [
        'active' => 'boolean',
    ];

    protected $fillable = [
        'key',
        'value',
        'type',
        'description',
        'group',
        'active',
    ];

    public function value(): Attribute
    {
        return Attribute::get(fn () => $this->castValue());
    }

    private function castValue()
    {
        $rawValue = Arr::get($this->attributes, 'value');

        return match ($this->type) {
            'integer' => (int) $rawValue,
            'float' => (float) $rawValue,
            'boolean' => (bool) $rawValue,
            'json' => json_decode($rawValue, true),
            default => $rawValue,
        };
    }


    public function newEloquentBuilder($query): SettingQueryBuilder
    {
        return new SettingQueryBuilder($query);
    }
}
