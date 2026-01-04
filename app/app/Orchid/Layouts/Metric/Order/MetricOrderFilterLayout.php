<?php

namespace app\Orchid\Layouts\Metric\Order;

use App\Orchid\Filters\Metric\MetricCreatedAtFilter;
use Orchid\Screen\Layouts\Selection;

class MetricOrderFilterLayout extends Selection
{
    public function filters(): array
    {
        return [
            MetricCreatedAtFilter::class,
        ];
    }
}
