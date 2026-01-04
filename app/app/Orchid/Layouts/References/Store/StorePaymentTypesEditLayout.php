<?php

namespace App\Orchid\Layouts\References\Store;

use App\Orchid\Fields\Matrix;
use Domain\Order\Models\Payment\PaymentType;
use Illuminate\Contracts\Container\BindingResolutionException;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Layouts\Rows;

class StorePaymentTypesEditLayout extends Rows
{
    /**
     * Get the fields elements to be displayed.
     *
     * @return Field[]
     * @throws BindingResolutionException
     */
    protected function fields(): array
    {
        return [
            Matrix::make('store.payments')
                ->columns([
                    __('admin.payment_types') => 'id'
                ])
                ->fields([
                    'id' => Relation::make()
                        ->fromModel(PaymentType::class, 'title', 'id')
                ])
                ->addRowText(__('admin.store.payment_add_to_shop')),
        ];
    }
}
