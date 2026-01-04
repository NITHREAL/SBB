<?php

namespace Infrastructure\Services\Loyalty\Responses\Manzana\Cards;

use Illuminate\Support\Arr;
use Infrastructure\Services\Loyalty\Responses\Manzana\ManzanaResponseInterface;

readonly class ContactCard implements ManzanaResponseInterface
{
    public function __construct(
        private string $cardId,
        private string $contactId,
        private ?string $fullName,
        private string $number,
        private int $bonusType,
        private string $cardType,
        private string $cardTypeId,
        private string $statusDate,
        private string $expiryDate,
        private string $balance,
        private bool $isVirtual,
    ) {
    }

    public static function make(array $data): self
    {
        return new self(
            Arr::get($data, 'Id'),
            Arr::get($data, 'ContactId'),
            Arr::get($data, 'FullName'),
            Arr::get($data, 'Number'),
            Arr::get($data, 'BonusType'),
            Arr::get($data, 'CardType'),
            Arr::get($data, 'CardTypeId'),
            Arr::get($data, 'StatusDate'),
            Arr::get($data, 'ExpiryDate'),
            Arr::get($data, 'ActiveBalance'),
            Arr::get($data, 'IsVirtual') ?? false,
        );
    }

    public function getId(): string
    {
        return $this->cardId;
    }

    public function getContactId(): string
    {
        return $this->contactId;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function getNumber(): string
    {
        return $this->number;
    }

    public function getBonusType(): int
    {
        return $this->bonusType;
    }

    public function getCardType(): string
    {
        return $this->cardType;
    }

    public function getCardTypeId(): string
    {
        return $this->cardTypeId;
    }

    public function getStatusDate(): string
    {
        return $this->statusDate;
    }

    public function getExpiryDate(): string
    {
        return $this->expiryDate;
    }

    public function getBalance(): string
    {
        return $this->balance;
    }

    public function isVirtual(): bool
    {
        return $this->isVirtual;
    }
}
