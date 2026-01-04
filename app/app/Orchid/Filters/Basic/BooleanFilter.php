<?php

namespace App\Orchid\Filters\Basic;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Orchid\Screen\Fields\Select;

abstract class BooleanFilter extends BasicFilter
{
    protected $options = [
        null => 'Все',
        0  => 'Нет',
        1  => 'Да',
    ];

    public function run(Builder $builder): Builder
    {
        $column = $this->getDbColumn();
        $value = $this->getValue();

        if ($value !== null) {
            $builder = $builder->where($column, $value);
        }

        return $builder;
    }

    public function display(): array
    {
        $param = $this->getParam();
        $value = $this->getValue();
        $name = $this->name();

        return [
            Select::make($param)
                ->title($name)
                ->value($value)
                ->options(array_merge([null => 'Все'], $this->options))
        ];
    }

    /**
     * @return string
     */
    public function value(): string
    {
        $name = $this->name();
        $value = $this->getValue();

        return $name . ': ' . Arr::get($this->options, $value);
    }
}
