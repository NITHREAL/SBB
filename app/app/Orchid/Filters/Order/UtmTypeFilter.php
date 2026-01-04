<?php

namespace App\Orchid\Filters\Order;

use App\Orchid\Filters\Basic\SelectFilter;
use Domain\UtmLabel\Enums\UtmLabelEnum;
use Illuminate\Database\Eloquent\Builder;

class UtmTypeFilter extends SelectFilter
{
    protected bool $multiple = true;

    public $parameters = [
        'utm_type'
    ];

    public function __construct()
    {
        parent::__construct();

        $this->options = UtmLabelEnum::toArrayWithValues();
    }

    public function name(): string
    {
        return 'utm ' . __('admin.utm.type');
    }


    public function run(Builder $builder): Builder
    {
        $value = $this->getValue();

        $multiple = $this->multiple;
        if ($value) {
            $builder->whereHas('utm', function ($query) use ($value, $multiple) {
                if ($multiple) {
                    $query->whereIn('type', $value);
                } else {
                    $query->where('type', $value);
                }
            });
        }

        return $builder;
    }
}
