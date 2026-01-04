<?php

namespace App\Orchid\Layouts\References\PaymentType;

use App\Orchid\Core\Actions;
use App\Orchid\Helpers\TD\Active;
use App\Orchid\Helpers\TD\ID;
use App\Orchid\Helpers\TD\Sort;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class PaymentTypeListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'payments';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): array
    {
        return [
            ID::make()->sort(),
            Active::make()->sort(),
            TD::make('title', __('admin.title'))->sort(),
            TD::make('code', __('admin.payment_type.code'))->sort(),
//            TD::make('delivery_type', __('admin.payment_type.delivery_type'))->sort(),
            Sort::make()->sort(),
            TD::make()->actions([
                new Actions\Activate(),
                new Actions\Edit('platform.payment_types.edit'),
            ])
        ];
    }
}
