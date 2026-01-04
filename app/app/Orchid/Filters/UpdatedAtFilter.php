<?php

namespace App\Orchid\Filters;

use App\Orchid\Screens\Fields\DateTimeRange;
use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;
use Orchid\Screen\Field;

class UpdatedAtFilter extends Filter
{
    /**
     * @var array
     */
    public $parameters = [
        'updated_at'
    ];

    /**
     * @return string
     */
    public function name(): string
    {
        return __('admin.updated_at');
    }

    /**
     * @param Builder $builder
     *
     * @return Builder
     */
    public function run(Builder $builder): Builder
    {
        return $builder
            ->where('updated_at', '>=', $this->request->get('updated_at')['start'])
            ->where('updated_at', '<=', $this->request->get('updated_at')['end']);
    }

    /**
     * @return Field[]
     */
    public function display(): array
    {
        return [
            DateTimeRange::make('updated_at')
                ->value($this->request->get('updated_at'))
                ->enableTime()
                ->format24hr()
                ->title(__('admin.updated_at'))
        ];
    }
}
