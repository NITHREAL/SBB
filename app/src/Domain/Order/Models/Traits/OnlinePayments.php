<?php

namespace Domain\Order\Models\Traits;

use Domain\Order\Enums\Payment\PaymentStatusEnum;
use Domain\Order\Models\Payment\OnlinePayment;
use Illuminate\Support\Collection;

trait OnlinePayments
{
    public function getHeldOnlinePayments(): Collection
    {
        return $this->payments()
            ->where('online_payments.status', PaymentStatusEnum::hold()->value)
            ->get();
    }

    public function getPayedOnlinePayments(): Collection
    {
        return $this->payments()
            ->whereIn('online_payments.status', [PaymentStatusEnum::hold()->value, PaymentStatusEnum::deposit()->value])
            ->get();
    }

    public function getDepositedOnlinePayments(): Collection
    {
        return $this->payments()
            ->where('online_payments.status', PaymentStatusEnum::deposit()->value)
            ->get();
    }

    public function getDeclinedOnlinePayments(): Collection
    {
        return $this->payments()
            ->where('online_payments.status', PaymentStatusEnum::decline()->value)
            ->get();
    }

    public function getLastOnlinePayment(): ?OnlinePayment
    {
        return $this->payments()
            ->whereIn(
                'online_payments.status',
                [
                    PaymentStatusEnum::registered()->value,
                    PaymentStatusEnum::hold()->value,
                ],
            )
            ->orderByDesc('online_payments.created_at')
            ->first();
    }

    public function getLastRegisteredOnlinePayment(): ?OnlinePayment
    {
        return $this->payments()
            ->where('online_payments.status', PaymentStatusEnum::registered()->value)
            ->orderByDesc('online_payments.created_at')
            ->first();
    }

    public function getLastHeldOnlinePayment()
    {
        return $this->payments()
            ->where('online_payments.status', PaymentStatusEnum::hold()->value)
            ->orderByDesc('online_payments.created_at')
            ->first();
    }
}
