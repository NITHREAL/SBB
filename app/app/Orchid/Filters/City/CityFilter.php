<?php

namespace App\Orchid\Filters\City;

use App\Orchid\Filters\Basic\RelationFilter;
use Domain\City\Models\City;

class CityFilter extends RelationFilter
{
    public $parameters = [
        'city_id'
    ];

    protected string $modelClassName = City::class;

    protected string $modelColumnName = 'title';

    protected bool $multiple = true;

    public function name(): string
    {
        return __('admin.cities');
    }
}
