<?php

namespace App\Orchid\Filters\Order;

use App\Orchid\Filters\Basic\TextFilter;
use Illuminate\Database\Eloquent\Builder;

class UtmValueFilter extends TextFilter
{
    public $parameters = [
        'utm_value'
    ];

    public function name(): string
    {
        return 'utm ' . __('admin.utm.value');
    }


    public function run(Builder $builder): Builder
    {
        $value = $this->getValue();

        if ($value) {
            $builder->whereHas('utm', function ($query) use ($value) {
                $value = $this->getValue();
                return $query->whereRaw("lower(value) like (?)", ["%{$value}%"]);
            });
        }

        return $builder;
    }
}
