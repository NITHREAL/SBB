<?php

namespace App\Orchid\Layouts\Shop\Promo;

use Domain\Promocode\Enums\PromocodeDeliveryTypeEnum;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Rows;

class PromoInfoListener extends Rows
{
    protected $targets = [
        'promo.free_delivery'
    ];

    protected function fields(): iterable
    {
        $isFreeDelivery = $this->query->get('is_free_delivery') || $this->query->get('promo.free_delivery');

        return [
            CheckBox::make('promo.percentage')
                ->title(__('admin.promo.percentage'))
                ->sendTrueOrFalse()
                ->canSee(!$isFreeDelivery)
                ->horizontal(),
            Input::make('promo.discount')
                ->title(__('admin.promo.discount'))
                ->type('number')
                ->min(0)
                ->required(!$isFreeDelivery)
                ->canSee(!$isFreeDelivery)
                ->help('При процентном значении размер скидки должен быть не более 100')
                ->horizontal(),
            CheckBox::make('promo.any_product')
                ->title(__('admin.promo.any_product'))
                ->sendTrueOrFalse()
                ->checked($isFreeDelivery)
                ->disabled($isFreeDelivery)
                ->horizontal(),
            Select::make('promo.delivery_type')
                ->title(__('admin.promo.delivery_type'))
                ->options(PromocodeDeliveryTypeEnum::toArray())
                ->value($isFreeDelivery ?
                    PromocodeDeliveryTypeEnum::delivery()->value :
                    PromocodeDeliveryTypeEnum::any()->value)
                ->disabled($isFreeDelivery)
                ->horizontal(),
        ];
    }
}
