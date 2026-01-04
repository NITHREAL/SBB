<?php

namespace Domain\Order\DTO\Payment;

use Domain\User\Models\User;
use Infrastructure\DTO\BaseDTO;

class OnlinePaymentBindingDTO extends BaseDTO
{
    public function __construct(
        private readonly string $bindingId,
        private readonly int $userId,
        private readonly string $acquiringType,
        private readonly OnlinePaymentBindingCardDataDTO $cardData,
    ) {
    }

    public static function make(
        string $bindingId,
        int $userId,
        string $acquiringType,
        array $cardData
    ): self {
        $cardDataDTO = OnlinePaymentBindingCardDataDTO::make($cardData);

        return new self(
            $bindingId,
            $userId,
            $acquiringType,
            $cardDataDTO,
        );
    }

    public function getBindingId(): string
    {
        return $this->bindingId;
    }

    public function getUser(): User
    {
        return User::findOrFail($this->userId);
    }

    public function getExpiryDate(): string
    {
        return sprintf(
            '%s/%s',
            $this->cardData->getExpiryMonth(),
            $this->cardData->getExpiryYear(),
        );
    }

    public function getCardDescription(): string
    {
        return sprintf(
            '%s *%s',
            $this->cardData->getCardType(),
            $this->cardData->getLastChars(),
        );
    }

    public function getAcquiringType(): string
    {
        return $this->acquiringType;
    }

    public function getCardData(): OnlinePaymentBindingCardDataDTO
    {
        return $this->cardData;
    }
}

