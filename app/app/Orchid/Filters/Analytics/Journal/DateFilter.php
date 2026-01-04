<?php

declare(strict_types=1);

namespace App\Orchid\Filters\Analytics\Journal;

use App\Orchid\Screens\Fields\DateTimeRange;
use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;

class DateFilter extends Filter
{
    /**
     * @var array
     */
    public $parameters = [
        'completed_at'
    ];

    public function name(): string
    {
        return __('admin.journal.date');
    }

    public function run(Builder $builder): Builder
    {
        return $builder
            ->where('completed_at', '>=', $this->request->get('completed_at')['start'])
            ->where('completed_at', '<=', $this->request->get('completed_at')['end']);
    }

    public function display(): array
    {
        return [
            DateTimeRange::make('completed_at')
                ->value($this->request->get('completed_at'))
                ->enableTime()
                ->format24hr()
                ->makeStatic()
                ->title(__('admin.journal.date'))
        ];
    }
}
