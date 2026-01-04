<?php

namespace Domain\Order\Services;

use Domain\Order\Enums\OrderStatusEnum;
use Domain\Order\Enums\Payment\PaymentTypeEnum;
use Domain\Order\Exceptions\OrderException;
use Domain\Order\Helpers\OrderHelper;
use Domain\Order\Jobs\Payment\RefundPaymentJob;
use Domain\Order\Jobs\Payment\ReversePaymentJob;
use Domain\Order\Models\Order;
use Domain\User\Models\User;
use Domain\User\Services\Orders\UserOrderService;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

readonly class OrderCancelService
{
    public function __construct(
        private UserOrderService $userOrderService,
    ) {
    }

    /**
     * @throws OrderException
     */
    public function cancelOrder(int $orderId, User $user): object
    {
        $order = Order::findOrFail($orderId);

        $this->checkCancelAvailability($order, $user);

        try {
            DB::beginTransaction();

            $order->status = $this->getCancelStatus($order, $user);

            $order->save();

            if ($order->payment_type === PaymentTypeEnum::byOnline()->value) {
                $this->reverseOrderPayments($order);
            } elseif ($order->payment_type === PaymentTypeEnum::sbp()->value) {
                $this->refundOrderPayments($order);
            }

            DB::commit();
        } catch (Exception $exception) {
            Log::error(
                sprintf(
                    '%s. %s',
                    'Ошибка во время процесса отмены заказа',
                    $exception->getMessage())
            );

            DB::rollBack();
        }

        return $this->userOrderService->getPreparedOrder($order);
    }

    public function reverseOrderPayments(Order $order): void
    {
        $payments = $order->getHeldOnlinePayments();

        foreach ($payments as $payment) {
            ReversePaymentJob::dispatch($payment)->delay(30);
        }
    }

    public function refundOrderPayments(Order $order): void
    {
        $payments = $order->getHeldOnlinePayments();

        foreach ($payments as $payment) {
            RefundPaymentJob::dispatch($payment)->delay(30);
        }
    }

    /**
     * @throws OrderException
     */
    private function checkCancelAvailability(Order $order, User $user): void
    {
        if ($this->isCancelAvailableForUser($order, $user) === false) {
            throw new OrderException('Пользовтель не имеет права отменять данный заказ');
        }

        if (OrderHelper::isCancellationAvailable($order) === false) {
            throw new OrderException('Нельзя отменить заказ в текущем состоянии');
        }
    }

    private function isCancelAvailableForUser(Order $order, User $user): bool
    {
        return $order->user_id === $user->id;
    }

    private function getCancelStatus(Order $order, User $user): string
    {
        return $order->user_id === $user->id
            ? OrderStatusEnum::canceledByCustomer()->value
            : OrderStatusEnum::canceled()->value;
    }
}
