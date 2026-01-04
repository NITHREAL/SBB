<?php

namespace Domain\Order\QueryBuilders;

use Domain\Order\DTO\Payment\OnlinePaymentBindingCardDataDTO;
use Infrastructure\Eloquent\Builder\BaseQueryBuilder;

/**
 * @method static whereBindingId(string $bindingId)
 * @method static whereUserId(int $userId)
 */
class OnlinePaymentBindingQueryBuilder extends BaseQueryBuilder
{
    public function whereAquiringBindingId(string $aquiringBindingId): self
    {
        return $this->where('online_payment_bindings.acquiring_binding_id', $aquiringBindingId);
    }

    public function whereUser(int $userId): self
    {
        return $this->where('online_payment_bindings.user_id', $userId);
    }

    public function whereAcquiringType(string $acquiringType): self
    {
        return $this->where('online_payment_bindings.acquiring_type', $acquiringType);
    }

    public function whereAcquiringIdAndUser(string $acquiringBindingId, int $userId): self
    {
        return $this
            ->whereAquiringBindingId($acquiringBindingId)
            ->whereUser($userId);
    }

    public function whereAcquiringCardData(int $userId, OnlinePaymentBindingCardDataDTO $bindingCardDataDTO): self
    {
        return $this
            ->where('online_payment_bindings.user_id', $userId)
            ->where('online_payment_bindings.first_chars', $bindingCardDataDTO->getFirstChars())
            ->where('online_payment_bindings.last_chars', $bindingCardDataDTO->getLastChars())
            ->where('online_payment_bindings.card_type', $bindingCardDataDTO->getCardType());
    }
}
