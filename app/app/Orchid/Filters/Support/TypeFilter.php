<?php

namespace App\Orchid\Filters\Support;

use App\Orchid\Filters\Basic\BooleanFilter;
use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;
use Orchid\Screen\Fields\Select;

class TypeFilter extends Filter
{
    public $parameters = [
        'status',
    ];

    public function name(): string
    {
        return __('admin.type');
    }

    /**
     * Apply filter to the query.
     *
     * @param  Builder  $builder
     *
     * @return Builder
     */
    public function run(Builder $builder): Builder
    {
        $status = $this->request->get('status');

        if ($status == 1) {
            $builder->whereHas('supportMessages', function ($query) {
                $query->where('viewed', false);
                $query->where('author', 'user');
            });
        } elseif ($status == 2) {
            $builder->whereHas('supportMessages', function ($query) {
                $query->where('viewed', true);
                $query->where('author', 'user');
            });
        }

        return $builder;
    }

    /**
     * Get the displayable filter field.
     *
     * @return array
     */
    public function display(): array
    {
        return [
            Select::make('status')
                ->options([
                    0 => 'Все',
                    1 => 'Не прочитанные',
                    2 => 'Прочитанные',
                ])
                ->title($this->name())
                ->empty(),
        ];
    }

    /**
     * Get the value representation of the filter.
     *
     * @return string
     */
    public function value(): string
    {
        $status = $this->request->get('status');

        $statuses = [
            0 => 'Все',
            1 => 'Не прочитанные',
            2 => 'Прочитанные',
        ];

        return $this->name() . ': ' . ($statuses[$status] ?? 'Все');
    }
}

