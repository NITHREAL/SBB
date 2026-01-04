<?php

namespace App\Orchid\Filters\Basic;

use Illuminate\Database\Eloquent\Builder;
use Orchid\Screen\Fields\Input;

abstract class TextFilter extends BasicFilter
{
    public function run(Builder $builder): Builder
    {
        $column = $this->getDbColumn();
        $value = $this->getValue();

        return $builder->whereRaw("lower($column) like (?)", ["%{$value}%"]);
    }

    public function display(): array
    {
        $param = $this->getParam();
        $value = $this->getValue();
        $name = $this->name();

        return [
            Input::make($param)
                ->type('text')
                ->value($value)
                ->title($name)
        ];
    }
}
