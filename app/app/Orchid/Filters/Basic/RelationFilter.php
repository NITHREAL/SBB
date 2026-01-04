<?php

namespace App\Orchid\Filters\Basic;

use Illuminate\Database\Eloquent\Builder;
use Orchid\Screen\Fields\Relation;

abstract class RelationFilter extends BasicFilter
{
    protected string $modelClassName;

    protected string $modelColumnName;

    protected string $modelColumnKey = 'id';

    protected array $modelSearchColumns = [];

    protected ?string $modelDisplayAppend = null;

    protected bool $multiple = false;

    public function run(Builder $builder): Builder
    {
        $column = $this->getDbColumn();
        $value = $this->getValue();

        if ($this->multiple) {
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

        $relation = Relation::make($param)
            ->title($name)
            ->fromModel($this->modelClassName, $this->modelColumnName, $this->modelColumnKey)
            ->value($value);

        if ($this->multiple) {
            $relation->multiple();
        }

        if ($this->modelSearchColumns) {
            $relation->searchColumns(...$this->modelSearchColumns);
        }

        if ($this->modelDisplayAppend) {
            $relation->displayAppend($this->modelDisplayAppend);
        }

        return [
            $relation
        ];
    }

    public function value(): string
    {
        $value = $this->getValue();
        $name = $this->name();

        /** @var Builder $builder */
        $builder = $this->modelClassName::query();

        if (is_array($value)) {
            $builder->whereIn($this->modelColumnKey, $value);
        } else {
            $builder->where($this->modelColumnKey, $value);
        }

        $collection = $builder->get();
        $displayColumn = $this->modelDisplayAppend ?? $this->modelColumnName;
        $valueLabel = $collection
            ->pluck($displayColumn)
            ->flatten()
            ->implode(static::$delimiter);


        return $name . ': ' . $valueLabel;
    }
}
