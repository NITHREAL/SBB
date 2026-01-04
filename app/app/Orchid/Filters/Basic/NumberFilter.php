<?php

namespace App\Orchid\Filters\Basic;

use Illuminate\Database\Eloquent\Builder;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;

abstract class NumberFilter extends BasicFilter
{
    public function run(Builder $builder): Builder
    {
        $column = $this->getDbColumn();
        $value = $this->getValue();

        return $builder->where($column, $value);
    }

    /**
     * @return Field[]
     */
    public function display(): array
    {
        $param = $this->getParam();
        $value = $this->getValue();
        $name = $this->name();

        return [
            Input::make($param)
                ->type('number')
                ->value($value)
                ->title($name)
        ];
    }
}
