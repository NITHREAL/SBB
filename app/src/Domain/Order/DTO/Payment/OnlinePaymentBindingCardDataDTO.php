<?php

namespace Domain\Order\DTO\Payment;

use Illuminate\Support\Arr;
use Infrastructure\DTO\BaseDTO;

class OnlinePaymentBindingCardDataDTO extends BaseDTO
{
    public function __construct(
        private readonly ?string $firstChars,
        private readonly ?string $lastChars,
        private readonly ?string $cardType,
        private readonly ?string $expiryMonth,
        private readonly ?string $expiryYear,
    ) {
    }

    public static function make(array $data): self
    {
        return new self(
            Arr::get($data, 'firstChars'),
            Arr::get($data, 'lastChars'),
            Arr::get($data, 'cardType'),
            Arr::get($data, 'expirationMonth'),
            Arr::get($data, 'expirationYear'),
        );
    }

    public function getFirstChars(): ?string
    {
        return $this->firstChars;
    }

    public function getLastChars(): ?string
    {
        return $this->lastChars;
    }

    public function getCardType(): ?string
    {
        return $this->cardType;
    }

    public function getExpiryMonth(): ?string
    {
        return $this->expiryMonth;
    }

    public function getExpiryYear(): ?string
    {
        return $this->expiryYear;
    }
}
