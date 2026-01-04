<?php

namespace App\Orchid\Filters\Order;

use App\Orchid\Filters\Basic\RelationFilter;
use Domain\City\Models\City;
use Illuminate\Database\Eloquent\Builder;

class OrderCityFilter extends RelationFilter
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

    public function run(Builder $builder): Builder
    {
        $value = $this->getValue();

        $multiple = $this->multiple;
        $builder->whereHas('store', function ($query) use ($value, $multiple) {
            if ($multiple) {
                $query->whereIn('city_id', $value);
            } else {
                $query->where('city_id', $value);
            }
        });

        return $builder;
    }
}
