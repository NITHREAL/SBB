<?php

namespace app\Orchid\Filters\Metric;

use DateTime;
use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;

class MetricCreatedAtFilter extends Filter
{
    /**
     * @var array
     */
    public $parameters = [
        'created_at_from',
        'created_at_to',
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
        return $builder
            ->when($this->request->get('created_at_from'), function ($query, $createdAtFrom) {
                $dateFrom = DateTime::createFromFormat('d-m-Y', $createdAtFrom);
                if ($dateFrom) {
                    $query->where('created_at', '>=', $dateFrom->format('Y-m-d'));
                }
            })
            ->when($this->request->get('created_at_to'), function ($query, $createdAtTo) {
                $dateTo = DateTime::createFromFormat('d-m-Y', $createdAtTo);
                if ($dateTo) {
                    $query->where('created_at', '<=', $dateTo->format('Y-m-d') . ' 23:59:59');
                }
            });
    }

    /**
     * @return Field[]
     */
    public function display(): array
    {
        return [
            Input::make('created_at_from')
                ->type('date')
                ->value($this->request->get('created_at_from'))
                ->title(__('admin.created_at_from'))
                ->mask([
                    'alias' => 'datetime',
                    'inputFormat' => 'dd-mm-yyyy',
                    'placeholder' => 'дд-мм-гггг',
                ]),

            Input::make('created_at_to')
                ->type('date')
                ->value($this->request->get('created_at_to'))
                ->title(__('admin.created_at_to'))
                ->mask([
                    'alias' => 'datetime',
                    'inputFormat' => 'dd-mm-yyyy',
                    'placeholder' => 'дд-мм-гггг',
                ]),
        ];
    }
}
