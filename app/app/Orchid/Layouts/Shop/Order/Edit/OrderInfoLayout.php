<?php

namespace App\Orchid\Layouts\Shop\Order\Edit;

use Domain\Order\Enums\Delivery\DeliveryTypeEnum;
use Domain\Order\Enums\OrderStatusEnum;
use Domain\Order\Enums\Payment\PaymentTypeEnum;
use Domain\Order\Helpers\OrderHelper;
use Domain\Order\Models\Order;
use Domain\Order\Models\Payment\PaymentType;
use Domain\Store\Models\Store;
use Domain\User\Models\User;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Label;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Rows;

class OrderInfoLayout extends Rows
{
    protected function fields(): array
    {
        /** @var Order $order */
        $order = $this->query['order'];
        $completed = $order->isCompleted;
        $orderTotal = OrderHelper::getTotal($order, true);

        return [
            Label::make('order.id')
                ->title(__('admin.id'))
                ->horizontal()
                ->canSee($order->exists),

            Label::make('order.system_id')
                ->title(__('admin.system_id'))
                ->horizontal()
                ->value($order->getAttribute('system_id') ?: 'Заказ не выгружен в 1С')
                ->canSee($order->exists),

            Relation::make('order.store_system_id')
                ->title(__('admin.order.store'))
                ->fromClass(Store::class, 'title', 'system_id')
                ->required()
                ->horizontal()
                ->canSee(!$order->exists),

            Select::make('order.store_system_id')
                ->title(__('admin.order.store'))
                ->fromQuery(
                    Store::query()
                        ->where('city_id', '=', $order->store?->city?->id)
                        ->where('active', '=', true),
                    'title',
                    'system_id'
                )
                ->value($order->store?->system_id)
                ->required()
                ->horizontal()
                ->canSee(
                    $order->delivery_type !== DeliveryTypeEnum::pickup()->value
                    && !$completed
                    && $order->exists
                )
                ->when((bool)$order->store?->is_dark_store, function (Select $select) {
                    $select->help(
                        'Выбран служебный магазин.
                        Необходимо выбрать конкретный магазин, т.к. доставка из служебного магазина невозможна.
                        С выбранным служебным магазином, заказ не будет выгружен в 1С.
                        Выбор магазинов доступен в пределах города, в котором текущий служебный магазин.'
                    );
                }),

            Label::make('order.store.title')
                ->title(__('admin.order.store'))
                ->horizontal()
                ->canSee($order->exists && ($order->delivery_type === DeliveryTypeEnum::pickup()->value)),

            Relation::make('order.user_id')
                ->title(__('admin.order.user'))
                ->fromClass(User::class, 'id')
                ->displayAppend('full')
                ->required()
                ->horizontal()
                ->canSee(!$order->exists),

            Label::make('order.user.full_name')
                ->title(__('admin.order.user'))
                ->canSee($order->exists)
                ->horizontal(),

            Select::make('order.status')
                ->options(OrderStatusEnum::toArray())
                ->title(__('admin.order.status'))
                ->required()
                ->disabled()
                ->horizontal(),

            Select::make('order.payment_type')
                ->fromModel(PaymentType::class, 'title', 'code')
                ->title(__('admin.order.payment_type'))
                ->required()
                ->disabled($completed)
                ->horizontal(),

            Label::make('')
                ->title(__('admin.order.promo'))
                ->value($this->getPromoCode($order) ?: 'Без промокода')
                ->horizontal(),

            Input::make('order.delivery_cost')
                ->title(__('admin.order.delivery_price'))
                ->type('number')
                ->min(0)
                ->step(1)
                ->horizontal()
                ->disabled($completed),

            Label::make('order.total')
                ->value($orderTotal)
                ->title(__('admin.order.total'))
                ->horizontal(),

            Label::make('order.created_at')
                ->title(__('admin.created_at'))
                ->horizontal()
                ->canSee($order->exists)
                ->value($order->created_at->format('d-m-Y')),

            Label::make('order.updated_at')
                ->title(__('admin.updated_at'))
                ->horizontal()
                ->canSee($order->exists),

            Label::make('order.completed_at')
                ->title(__('admin.order.completed_at'))
                ->horizontal()
                ->canSee($order->exists && $completed),

            Label::make('order.preparedDeliverySubType')
                ->title(__('admin.order.delivery_sub_type'))
                ->horizontal()
                ->canSee($order->exists),

            Label::make('order.reviews.0.rate')
                ->title(__('admin.order.review.rate'))
                ->horizontal()
                ->canSee($order->exists),

            Label::make('order.reviews.0.text')
                ->title(__('admin.order.review.text'))
                ->horizontal()
                ->canSee($order->exists),
        ];
    }

    private function getPromoCode(Order $order): string
    {
        $result = '';
        $promo = $order->promocode;

        if ($promo) {
            $result = "{$promo->code} (Скидка: $promo->discount" . ($promo->percentage ? '%)' : ' руб.)');
        }

        return $result;
    }
}

