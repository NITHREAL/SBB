<?php

namespace Domain\Order\QueryBuilders;

use Infrastructure\Eloquent\Builder\BaseQueryBuilder;

/**
 * @method static self whereSberId(string $sberOrderId)
 * @method static self whereBatch(int $batch)
 */
class OnlinePaymentQueryBuilder extends BaseQueryBuilder
{
    public function whereSberId(string $sberOrderId): self
    {
        return $this->where('online_payments.sber_order_id', $sberOrderId);
    }

    public function whereBatch(string $batch): self
    {
        return $this
            ->leftJoin('order_payment', 'order_payment.payment_id', 'online_payments.id')
            ->where('batch', $batch);
    }
}
