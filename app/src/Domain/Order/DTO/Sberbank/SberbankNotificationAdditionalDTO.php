<?php

namespace Domain\Order\DTO\Sberbank;

use Illuminate\Support\Arr;
use Infrastructure\DTO\BaseDTO;

class SberbankNotificationAdditionalDTO extends BaseDTO
{
    public function __construct(
        private readonly ?string $phone,
        private readonly ?string $subscriptionId,
        private readonly ?string $memberId,
        private readonly ?string $extTransactionId,
        private readonly ?string $qrcId,
    ) {
    }

    public static function make(array $data): self
    {
        return new self(
            Arr::get($data, 'phone'),
            Arr::get($data, 'subscriptionId'),
            Arr::get($data, 'memberId'),
            Arr::get($data, 'extTransactionId'),
            Arr::get($data, 'qrcId'),
        );
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function getSubscriptionId(): ?string
    {
        return $this->subscriptionId;
    }

    public function getMemberId(): ?string
    {
        return $this->memberId;
    }

    public function getExtTransactionId(): ?string
    {
        return $this->extTransactionId;
    }

    public function getQrcId(): ?string
    {
        return $this->qrcId;
    }
}
