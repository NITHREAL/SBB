<?php

namespace Domain\Order\Observers;

use Domain\Order\Enums\OrderStatusEnum;
use Domain\Order\Helpers\Delivery\OrderDeliveryHelper;
use Domain\Order\Helpers\Payment\PaymentHelper;
use Domain\Order\Jobs\Exchange\Courier\PublishOrderToAmqp as PublishToCourier;
use Domain\Order\Jobs\Exchange\ExportOrderTo1CJob;
use Domain\Order\Jobs\Exchange\Picker\PublishOrderToAmqp as PublishToPicker;
use Domain\Order\Jobs\Sbermarket\OrderSbermarketStatusJob;
use Domain\Order\Models\Order;
use Domain\Order\Services\Exchange\OrderExchangeService;
use Domain\Order\Services\OrderProcessService;

class OrderObserver
{
    public function __construct(
        protected OrderProcessService $orderProcessService,
        private readonly OrderExchangeService $orderExchangeService,
    ) {
    }

    public function saved(Order $order): void
    {
        if (!empty($order->sm_original_order_id)) {
            $this->updateStatus($order);
        }

        if (PaymentHelper::isPaymentOnline($order->payment_type)) {
            if ($order->status === OrderStatusEnum::surcharge()->value) {
                $this->orderProcessService->processOrderSurcharged($order);
            } elseif ($order->status === OrderStatusEnum::completed()->value) {
                $this->orderProcessService->processOrderCompleted($order);
            } elseif ($order->status === OrderStatusEnum::collected()->value) {
                $this->orderProcessService->processOrderCollected($order);
            }
        }

        if (
            $order->isDirty('status')
            && $order->status !== OrderStatusEnum::waitingPayment()->value
        ) {
            ExportOrderTo1CJob::dispatch($order)->afterCommit();
        }

        if (
            $order->system_id
            && in_array($order->status, [OrderStatusEnum::accepted()->value, OrderStatusEnum::created()->value, OrderStatusEnum::payed()->value])
        ) {
            PublishToPicker::dispatch($order);
        }

        if (
            $order->system_id
            && in_array($order->status, [OrderStatusEnum::collected()->value, OrderStatusEnum::collecting()->value])
            && OrderDeliveryHelper::isDelivery($order->delivery_type)
        ) {
            PublishToCourier::dispatch($order);
        }
    }

    protected function updateStatus(Order $order): void
    {
        switch ($order->status) {
            case OrderStatusEnum::collecting()->value:
                OrderSbermarketStatusJob::dispatch($order, 'order.in_work')->afterCommit();
                break;
            case OrderStatusEnum::collected()->value:
                OrderSbermarketStatusJob::dispatch($order, 'order.ready_for_delivery')->afterCommit();
                break;
            case OrderStatusEnum::canceledByCustomer()->value:
            case OrderStatusEnum::canceled()->value:
                OrderSbermarketStatusJob::dispatch($order, 'order.canceled')->afterCommit();
                break;
        }
    }
}
