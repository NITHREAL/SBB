<?php

namespace Domain\Order\DTO\Yookassa;

use Illuminate\Support\Arr;
use Infrastructure\DTO\BaseDTO;

class YookassaNotificationObjectDTO extends BaseDTO
{
    public function __construct(
        private readonly string $id,
        private readonly string $status,
        private readonly bool $paid,
        private readonly float $amount,
    ) {
    }

    public static function make(array $data): self
    {
        $amountData = Arr::get($data, 'amount');

        return new self(
            Arr::get($data, 'id'),
            Arr::get($data, 'status'),
            (bool) Arr::get($data, 'paid'),
            (float) Arr::get($amountData, 'value'),
        );
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function isPaid(): bool
    {
        return $this->paid;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }
}
