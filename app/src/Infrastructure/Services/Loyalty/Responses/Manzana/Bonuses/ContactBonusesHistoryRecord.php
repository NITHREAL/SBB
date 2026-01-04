<?php

namespace Infrastructure\Services\Loyalty\Responses\Manzana\Bonuses;

use Illuminate\Support\Arr;

readonly class ContactBonusesHistoryRecord
{
    public function __construct(
        private string $contactId,
        private string $createdDate,
        private int $operationType,
        private string $ruleName,
        private string $debet,
        private string $credit,
        private ?string $parentName,
        private int $parentType,
        private int $originType,
    ) {
    }

    public static function make(array $data): self
    {
        return new self(
            Arr::get($data, 'ContactId'),
            Arr::get($data, 'CreatedDate'),
            Arr::get($data, 'OperationType'),
            Arr::get($data, 'RuleName'),
            Arr::get($data, 'Debet'),
            Arr::get($data, 'Credit'),
            Arr::get($data, 'ParentName'),
            Arr::get($data, 'ParentType'),
            Arr::get($data, 'OriginType'),
        );
    }

    public function getContactId(): string
    {
        return $this->contactId;
    }

    public function getCreatedDate(): string
    {
        return $this->createdDate;
    }

    public function getOperationType(): int
    {
        return $this->operationType;
    }

    public function getRuleName(): string
    {
        return $this->ruleName;
    }

    public function getDebet(): string
    {
        return $this->debet;
    }

    public function getCredit(): string
    {
        return $this->credit;
    }

    public function getParentName(): ?string
    {
        return $this->parentName;
    }

    public function getParentType(): int
    {
        return $this->parentType;
    }

    public function getOriginType(): int
    {
        return $this->originType;
    }

    public function getPreparedBonusesCount(): string
    {
        $debet = $this->getDebet();

        return (int) $debet > 0
            ? sprintf('+%s', $debet)
            : sprintf('-%s', $this->getCredit());
    }
}
