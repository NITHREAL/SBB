<?php

namespace App\Orchid\Filters\Basic;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Orchid\Screen\Fields\Select;

abstract class SelectFilter extends BasicFilter
{
    protected bool $multiple = false;

    protected array $options = [];

    public function run(Builder $builder): Builder
    {
        $column = $this->getDbColumn();
        $value = $this->getValue();

        if (is_array($value)) {
            $builder->whereIn($column, $value);
        } else {
            $builder->where($column, $value);
        }

        return $builder;
    }

    public function display(): array
    {
        $param = $this->getParam();
        $value = $this->getValue();
        $name = $this->name();

        $select = Select::make($param)
            ->title($name)
            ->value($value)
            ->options(array_merge([
                null => 'Все'
            ], $this->options));

        if ($this->multiple) {
            $select->multiple();
        }

        return [
            $select
        ];
    }

    public function value(): string
    {
        $value = $this->getValue();
        $name = $this->name();

        if (is_array($value)) {
            $valueLabel = array_intersect_key($this->options, array_flip($value));
            $valueLabel = implode(self::$delimiter, $valueLabel);
        } else {
            $valueLabel = Arr::get($this->options, $value);
        }

        return $name . ': ' . $valueLabel;
    }
}
