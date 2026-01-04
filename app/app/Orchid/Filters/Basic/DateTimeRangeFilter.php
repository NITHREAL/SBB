<?php

namespace App\Orchid\Filters\Basic;

use App\Orchid\Screens\Fields\DateTimeRange;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;

abstract class DateTimeRangeFilter extends BasicFilter
{
    public function run(Builder $builder): Builder
    {
        $column = $this->getDbColumn();
        $value = $this->getValue();
        $from = Arr::get($value, 'start');
        $to   = Arr::get($value, 'end');

        if ($from) {
            $builder->where($column, '>=', $from);
        }

        if ($to) {
            $builder->where($column, '<=', $to);
        }

        return $builder;
    }

    public function display(): array
    {
        $param = $this->getParam();
        $value = $this->getValue();
        $name = $this->name();

        return [
            DateTimeRange::make($param)
                ->value($value)
                ->enableTime()
                ->format24hr()
                ->title($name)
        ];
    }

    public function value(): string
    {
        $name = $this->name();
        $value = $this->getValue();

        $from = Arr::get($value, 'start');
        $to   = Arr::get($value, 'end');

        if ($from) {
            $from = Carbon::createFromFormat('Y-m-d H:i', $from)->format(config('platform.datetime_format'));
        }

        if ($to) {
            $to = Carbon::createFromFormat('Y-m-d H:i', $to)->format(config('platform.datetime_format'));
        }

        return "$name: От $from До $to";
    }
}
