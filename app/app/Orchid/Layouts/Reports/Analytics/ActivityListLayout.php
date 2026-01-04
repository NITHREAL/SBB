<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Reports\Analytics;

use App\Orchid\Helpers\TD\ID;
use Orchid\Screen\Layouts\Table;

class ActivityListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'analytic_activity';

    protected function columns(): array
    {
        return [
            ID::make('store_id', __('admin.activity.id')),
            ID::make('store.title', __('admin.activity.title')),
            ID::make('count_sales', __('admin.activity.count_sales')),
            ID::make('sum_sales', __('admin.activity.sum_sales')),
            ID::make('average_check', __('admin.activity.average_check')),
            ID::make('accrued_points', __('admin.activity.accrued_points')),
            ID::make('deducted_points', __('admin.activity.deducted_points')),
            ID::make('amount_gifts', __('admin.activity.amount_gifts')),
            ID::make('new_users', __('admin.activity.new_users')),
        ];
    }
}
