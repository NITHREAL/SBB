<?php

namespace App\Orchid\Layouts\Shop\Promo;

use Domain\Audience\Models\Audience;
use Domain\Promocode\Enums\PromocodeOrderTypeEnum;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Label;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Rows;

class PromoInfoRow extends Rows
{
    protected $target = 'promo';

    protected function fields(): array
    {
        $promo = $this->query['promo'];
        $usedCount = $promo->usedCount;

        return [
            Label::make('promo.id')
                ->title(__('admin.id'))
                ->horizontal()
                ->canSee($promo->exists),
            Input::make('promo.title')
                ->title(__('admin.promo.title'))
                ->horizontal(),
            Input::make('promo.description')
                ->title(__('admin.promo.description'))
                ->horizontal(),
            CheckBox::make('promo.active')
                ->title(__('admin.active'))
                ->sendTrueOrFalse()
                ->horizontal(),
            CheckBox::make('promo.free_delivery')
                ->title(__('admin.promo.free_delivery'))
                ->sendTrueOrFalse()
                ->horizontal(),
            Input::make('promo.code')
                ->title(__('admin.promo.code'))
                ->required()
                ->horizontal(),
            Select::make('promo.order_type')
                ->title(__('admin.promo.order_type'))
                ->options(PromocodeOrderTypeEnum::toArray())
                ->required()
                ->horizontal(),
            Input::make('promo.min_amount')
                ->title(__('admin.promo.min_amount'))
                ->type('number')
                ->min(0)
                ->step(1)
                ->horizontal(),
            DateTimer::make('promo.expires_in')
                ->title(__('admin.promo.expires_in'))
                ->format24hr()
                ->enableTime()
                ->horizontal(),
            Relation::make('promo.show_audience_id')
                ->fromModel(Audience::class, 'title')
                ->value($promo->show_audience_id)
                ->title('Аудитория для отображения')
                ->required(false)
                ->horizontal(),
            CheckBox::make('promo.any_user')
                ->title(__('admin.promo.any_user'))
                ->sendTrueOrFalse()
                ->horizontal(),
            Label::make('promo.used')
                ->title(__('admin.promo.used'))
                ->horizontal()
                ->canSee($promo->exists)
                ->value($usedCount ?: 'Не использовался'),
            CheckBox::make('promo.one_use_per_phone')
                ->title(__('admin.promo.one_use_per_phone'))
                ->sendTrueOrFalse()
                ->horizontal(),
            CheckBox::make('promo.only_one_use')
                ->title('Единоразовое применение')
                ->sendTrueOrFalse()
                ->horizontal(),
            Label::make('promo.total_discount')
                ->title(__('admin.promo.total_discount'))
                ->horizontal()
                ->canSee($promo->exists)
                ->value($promo->totalDiscount . ' руб.')
        ];
    }
}
