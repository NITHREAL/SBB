<?php

namespace App\Orchid\Filters;

use App\Orchid\Screens\Fields\DateTimeRange;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Orchid\Filters\Filter;
use Orchid\Screen\Field;

class CreatedAtFilter extends Filter
{
    /**
     * @var array
     */
    public $parameters = [
        'created_at'
    ];

    /**
     * @return string
     */
    public function name(): string
    {
        return __('admin.created_at');
    }

    /**
     * @param Builder $builder
     *
     * @return Builder
     */
    public function run(Builder $builder): Builder
    {
        $startDate = Carbon::parse($this->request->get('created_at')['start'])->startOfDay();
        $endDate = Carbon::parse($this->request->get('created_at')['end'])->endOfDay();

        return $builder
            ->where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate);
    }

    /**
     * @return Field[]
     */
    public function display(): array
    {
        return [
            DateTimeRange::make('created_at')
                ->enableTime()
                ->format24hr()
                ->title(__('admin.created_at'))
        ];
    }
}
