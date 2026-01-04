<?php

namespace Domain\Order\Services\Sbermarket;

use DateTime;
use Domain\Order\Builders\SbermarketOrderBuilder;
use Domain\Order\DTO\Sbermarket\SbermarketOrderDTO;
use Domain\Order\Enums\OrderStatusEnum;
use Domain\Order\Enums\Sbermarket\OrderSbermarketStatusEnum;
use Domain\Order\Exceptions\OrderException;
use Domain\Order\Exceptions\SbermarketException;
use Domain\Order\Models\Order;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SbermarketOrderService
{
    /**
     * @throws SbermarketException
     * @throws OrderException
     */
    public function handleSbermarketOrder(SbermarketOrderDTO $sbermarketOrderDTO): array
    {
        return match ($sbermarketOrderDTO->getEventType()) {
            'order.created'     => $this->createOrder($sbermarketOrderDTO),
            'order.updated'     => $this->updateOrder($sbermarketOrderDTO),
            'order.paid'        => $this->setOrderPayed($sbermarketOrderDTO),
            'order.delivering'  => $this->setOrderDelivering($sbermarketOrderDTO),
            'order.delivered'   => $this->setOrderDelivered($sbermarketOrderDTO),
            'order.canceled'    => $this->setOrderCanceled($sbermarketOrderDTO),
            default             => throw new SbermarketException(__('sbermarket.order.unknown_event_type')),
        };
    }

    /**
     * @throws OrderException
     */
    private function createOrder(SbermarketOrderDTO $sbermarketOrderDTO): array
    {
        try {
            DB::beginTransaction();

            $order = $this->getOrderBuilder($sbermarketOrderDTO)->create();

            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();

            $message = __('messages.sbermarket.order_create_error', ['message' => $exception->getMessage()]);

            Log::channel('sbermarket')->error($message);

            throw new OrderException($message);
        }

        return [
            'status'    => OrderSbermarketStatusEnum::created()->value,
            'number'    => $order->id,
        ];
    }

    /**
     * @throws SbermarketException
     */
    private function updateOrder(SbermarketOrderDTO $sbermarketOrderDTO): array
    {
        try {
            DB::beginTransaction();

            $sberOrderId = $sbermarketOrderDTO->getSberOrderId();

            $order = Order::query()->whereSberId($sberOrderId)->first();

            if ($order) {
                $deliveryExpectedTo = Arr::get($sbermarketOrderDTO->getDeliveryIntervalData(), 'expectedTo');

                $order->receive_date = new DateTime($deliveryExpectedTo);

                $order->save();
            }

            DB::commit();

            return [
                'status'    => OrderSbermarketStatusEnum::updated()->value,
                'number'    => $sberOrderId,
            ];
        } catch (Exception $exception) {
            DB::rollBack();

            $message = __('messages.sbermarket.order_update_error', ['message' => $exception->getMessage()]);

            Log::channel('sbermarket')->error($message);

            throw new SbermarketException($message);
        }
    }

    private function setOrderPayed(SbermarketOrderDTO $sbermarketOrderDTO): array
    {
        return [
            'status'    => OrderSbermarketStatusEnum::payed()->value,
            'number'    => $sbermarketOrderDTO->getSberOrderId(),
        ];
    }

    /**
     * @throws SbermarketException
     */
    private function setOrderDelivering(SbermarketOrderDTO $sbermarketOrderDTO): array
    {
        try {
            DB::beginTransaction();

            $sberOrderId = $sbermarketOrderDTO->getSberOrderId();

            $order = Order::query()->whereSberId($sberOrderId)->first();

            if ($order) {
                $order->status = OrderStatusEnum::delivering()->value;

                $order->save();
            }

            DB::commit();

            return [
                'status'    => OrderSbermarketStatusEnum::delivering()->value,
                'number'    => $sberOrderId,
            ];
        } catch (Exception $exception) {
            DB::rollBack();

            $message = __(
                'messages.sbermarket.order_status_change_error',
                ['status' => 'delivering', 'message' => $exception->getMessage()],
            );
            Log::channel('sbermarket')->error($message);

            throw new SbermarketException($message);
        }
    }

    /**
     * @throws SbermarketException
     */
    private function setOrderDelivered(SbermarketOrderDTO $sbermarketOrderDTO): array
    {
        try {
            DB::beginTransaction();

            $sberOrderId = $sbermarketOrderDTO->getSberOrderId();

            $order = Order::query()->whereSberId($sberOrderId)->first();

            if ($order) {
                $order->status = OrderStatusEnum::completed()->value;

                $order->save();
            }

            DB::commit();

            return [
                'status'    => OrderSbermarketStatusEnum::delivered()->value,
                'number'    => $sberOrderId,
            ];
        } catch (Exception $exception) {
            DB::rollBack();

            $message = __(
                'messages.sbermarket.order_status_change_error',
                ['status' => 'delivered', 'message' => $exception->getMessage()],
            );

            Log::channel('sbermarket')->error($message);

            throw new SbermarketException($message);
        }
    }

    /**
     * @throws SbermarketException
     */
    private function setOrderCanceled(SbermarketOrderDTO $sbermarketOrderDTO): array
    {
        try {
            DB::beginTransaction();

            $sberOrderId = $sbermarketOrderDTO->getSberOrderId();

            $order = Order::query()->whereSberId($sberOrderId)->first();

            if ($order) {
                $order->status = OrderStatusEnum::canceled()->value;

                $order->save();
            }

            DB::commit();

            return [
                'status'    => OrderSbermarketStatusEnum::canceled()->value,
                'number'    => $sberOrderId,
            ];
        } catch (Exception $exception) {
            DB::rollBack();

            $message = __(
                'messages.sbermarket.order_status_change_error',
                ['status' => 'canceled', 'message' => $exception->getMessage()],
            );

            Log::channel('sbermarket')->error($message);

            throw new SbermarketException($message);
        }
    }

    /**
     * @throws Exception
     */
    private function getOrderBuilder(SbermarketOrderDTO $sbermarketOrderDTO): SbermarketOrderBuilder
    {
        return (new SbermarketOrderBuilder())
            ->setUser($sbermarketOrderDTO->getCustomerData())
            ->setStore($sbermarketOrderDTO->getStoreId())
            ->setSberOrderId($sbermarketOrderDTO->getSberOrderId())
            ->setProductsData($sbermarketOrderDTO->getPositions())
            ->setAddressData($sbermarketOrderDTO->getAddressData())
            ->setDeliverySubType($sbermarketOrderDTO->getDeliveryIntervalData())
            ->setReceiveDateTime($sbermarketOrderDTO->getDeliveryIntervalData())
            ->setPaymentData($sbermarketOrderDTO->getTotalData())
            ->setComment($sbermarketOrderDTO->getComment(), $sbermarketOrderDTO->getSberOrderId());
    }
}
