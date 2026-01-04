<?php

namespace Domain\Order\QueryBuilders;

use Domain\Order\Enums\OrderSourceEnum;
use Domain\Order\Enums\OrderStatusEnum;
use Domain\Order\Enums\OrderTypeEnum;
use Domain\Order\Helpers\OrderStatusHelper;
use Domain\Order\Helpers\OrderTypeHelper;
use Infrastructure\Eloquent\Builder\BaseQueryBuilder;

/**
 * @method static whereUser(int $userId)
 * @method static wherePromocode(int $promocodeId)
 * @method static whereSource(string $source)
 * @method static whereType(string $type)
 * @method static whereNotCanceled()
 * @method static whereWaitingPayment()
 * @method static whereCompleted()
 * @method static whereOffline()
 * @method static whereNotOffline()
 * @method static whereStatePending()
 * @method static whereStateFinished()
 * @method static whereSberId(string $sberId)
 * @method static whereBatch(int $batch)
 * @method static whereNeedExchange()
 * @method static wherePaymentType(string $paymentType)
 * @method static whereOneCId(string $oneCId)
 */
class OrderQueryBuilder extends BaseQueryBuilder
{
    public function whereUser(int $userId): self
    {
        return $this->where('orders.user_id', $userId);
    }

    public function wherePromocode(int $promocodeId): self
    {
        return $this->where('orders.promo_id', $promocodeId);
    }

    public function whereSource(string $source): self
    {
        return $this->where('orders.request_from', $source);
    }

    public function whereType(string $type): self
    {
        $sourceTypes = match ($type) {
            OrderTypeEnum::offline()->value => OrderTypeHelper::getOfflineTypeSources(),
            OrderTypeEnum::online()->value  => OrderTypeHelper::getOnlineTypeSources(),
            default                         => OrderTypeHelper::getOnlineTypeSources(),
        };

        return $this->whereIn('orders.request_from', $sourceTypes);
    }

    public function whereNotCanceled(): self
    {
        return $this
            ->whereNotIn(
                'status',
                [
                    OrderStatusEnum::canceled()->value,
                    OrderStatusEnum::canceledByCustomer()->value,
                ]
            );
    }

    public function whereWaitingPayment(): self
    {
        return $this
            ->whereIn(
                'status',
                [
                    OrderStatusEnum::waitingPayment()->value,
                    OrderStatusEnum::surcharge()->value,
                ]
            );
    }

    public function whereCompleted(): self
    {
        return $this->where('status', OrderStatusEnum::completed()->value);
    }

    public function whereOffline(): self
    {
        return $this->where('orders.request_from', OrderSourceEnum::offline()->value);
    }

    public function whereNotOffline(): self
    {
        return $this->where('orders.request_from', '!=', OrderSourceEnum::offline()->value);
    }

    public function whereStatePending(): self
    {
        return $this->whereIn('orders.status', OrderStatusHelper::getPendingStatuses());
    }

    public function whereStateFinished(): self
    {
        return $this->whereIn('orders.status', OrderStatusHelper::getFinishedStatuses());
    }

    public function whereSberId(string $sberId): self
    {
        return $this->where('sm_original_order_id', $sberId);
    }

    public function whereBatch(string $batch): self
    {
        return $this->where('orders.batch', $batch);
    }

    public function whereNeedExchange(): self
    {
        return $this->where('orders.need_exchange', true);
    }

    public function wherePayment(string $paymentType): self
    {
        return $this->where('orders.payment_type', $paymentType);
    }

    public function whereOneCId(string $oneCId): self
    {
        return $this->where('orders.system_id', $oneCId);
    }

    public function whereNotCompleted(): self
    {
        return $this->whereIn('status', [
            OrderStatusEnum::created()->value,
            OrderStatusEnum::accepted()->value,
            OrderStatusEnum::collecting()->value,
            OrderStatusEnum::collected()->value,
            OrderStatusEnum::delivering()->value,
        ]);
    }
}
