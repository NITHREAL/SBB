<?php

namespace App\Orchid\Filters\Basic;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;

abstract class RangeFilter extends BasicFilter
{
    public function run(Builder $builder): Builder
    {
        $column = $this->getDbColumn();
        $value = $this->getValue();
        $from = Arr::get($value, 'from');
        $to   = Arr::get($value, 'to');

        if ($from == 0 && $to == 0) {
            $builder->where($column, '=', 0);
        } else {
            if ($from !== null) {
                $builder->where($column, '>=', $from);
            }
            if ($to !== null) {
                $builder->where($column, '<=', $to);
            }
        }

        return $builder;
    }

    /**
     * @return Field[]
     */
    public function display(): array
    {
        $param = $this->getParam();

        return [
            Input::make($param . '[from]')
                ->title('От')
                ->type('number')
                //->min(0)
                ->step(1),

            Input::make($param . '[to]')
                ->title('До')
                ->type('number')
                //->min(0)
                ->step(1),
        ];
    }

    /**
     * @return string
     */
    public function value(): string
    {
        $value = $this->getValue();
        $from = Arr::get($value, 'from');
        $to   = Arr::get($value, 'to');

        return "{$this->name()}: От $from До $to";
    }
}
