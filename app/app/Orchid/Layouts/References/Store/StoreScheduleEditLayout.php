<?php

namespace App\Orchid\Layouts\References\Store;

use Domain\Order\Models\Delivery\PolygonType;
use Infrastructure\Enum\DaysOfWeekFull;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Matrix;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Rows;

class StoreScheduleEditLayout extends Rows
{
    /**
     * Get the fields elements to be displayed.
     *
     * @return Field[]
     */
    protected function fields(): array
    {
        return [
            Input::make('store.work_time')
                ->title(__('admin.store.schedules.as_text'))
                ->horizontal(),

            \App\Orchid\Fields\Matrix::make('store.scheduleWeekdays')
                ->columns([
                    __('admin.store.schedules.polygon_type') => 'polygon_type_id',
                    __('admin.store.schedules.week_day') => 'week_day',
                    __('admin.store.schedules.from') => 'from',
                    __('admin.store.schedules.to') => 'to',
                    __('admin.store.schedules.not_working') => 'not_working'
                ])
                ->fields(
                    [
                        'id'                => Input::make('id')
                            ->hidden()
                            ->style('width: 0; padding: 0; margin: 0;'),
                        'polygon_type_id'   => Relation::make('polygon_type_id')
                            ->fromModel(PolygonType::class, 'title'),
                        'week_day'          => Select::make('select')
                            ->options(DaysOfWeekFull::toArray()),
                        'from'              => DateTimer::make()
                            ->format('H:i')
                            ->enableTime()
                            ->format24hr()
                            ->noCalendar()->placeholder('Выбрать время'),
                        'to'                    => DateTimer::make()
                            ->format('H:i')
                            ->enableTime()
                            ->format24hr()
                            ->noCalendar()->placeholder('Выбрать время'),
                        'not_working'           => CheckBox::make()->sendTrueOrFalse()
                    ])
                ->title(__('admin.store.schedules.for_week'))
                ->styles([
                    'id' => 'display: none;',
                ])
                ->setFirstCol(true),

            \App\Orchid\Fields\Matrix::make('store.scheduleDates')
                ->addRowText(__('admin.store.schedules.add_date'))
                ->columns([
                    __('admin.store.schedules.date') => 'date',
                    __('admin.store.schedules.polygon_type') => 'polygon_type_id',
                    __('admin.store.schedules.from') => 'from',
                    __('admin.store.schedules.to') => 'to',
                    __('admin.store.schedules.not_working') => 'not_working'
                ])
                ->fields([
                    'id'                => Input::make('id')
                        ->hidden()
                        ->style('width: 0; padding: 0; margin: 0;'),
                    'polygon_type_id'   => Relation::make('polygon_type_id')
                        ->fromModel(PolygonType::class, 'title'),
                    'date'              => DateTimer::make()
                        ->format('Y-m-d')
                        ->placeholder('Выбрать время'),
                    'from'              => DateTimer::make()
                        ->format('H:i')
                        ->enableTime()
                        ->format24hr()
                        ->noCalendar()
                        ->placeholder('Выбрать время'),
                    'to'                => DateTimer::make()
                        ->format('H:i')
                        ->enableTime()
                        ->format24hr()
                        ->noCalendar()
                        ->placeholder('Выбрать время'),
                    'not_working'       => CheckBox::make()->sendTrueOrFalse()
                ])
                ->title(__('admin.store.schedules.for_dates'))
                ->setFirstCol(true),
        ];
    }
}
