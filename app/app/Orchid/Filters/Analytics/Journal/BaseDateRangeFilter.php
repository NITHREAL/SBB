<?php

declare(strict_types=1);

namespace App\Orchid\Filters\Analytics\Journal;

use App\Orchid\Screens\Fields\DateTimeRange;
use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;

class BaseDateRangeFilter extends Filter
{
    /**
     * @var array
     */
    public $parameters = [
        'completed_at'
    ];

    protected ?string $column = '';

    protected ?string $field = '';

    public function column($column): static
    {
        $this->column = $column;
        return $this;
    }

    public function field($field): static
    {
        $this->field = $field;
        $this->parameters = [$this->field];
        return $this;
    }

    public function name(): string
    {
        return __('admin.journal.date');
    }

    public function run(Builder $builder): Builder
    {
        $_field = $this->field ?: $this->column;
        $_column = $this->column ?: $this->field;

        return $builder
            ->where($_column, '>=', $this->request->get($_field)['start'])
            ->where($_column, '<=', $this->request->get($_field)['end']);
    }

    public function display(): array
    {
        $_field = $this->field ?: $this->column;
        $_column = $this->column ?: $this->field;

        return [
            DateTimeRange::make($_column)
                ->value($this->request->get($_field))
                // ->enableTime()
                // ->format24hr()
                ->title(__('admin.journal.date'))
        ];
    }
}
