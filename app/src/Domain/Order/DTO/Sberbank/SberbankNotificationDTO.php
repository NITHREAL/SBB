<?php

namespace Domain\Order\DTO\Sberbank;

use Illuminate\Support\Arr;
use Infrastructure\DTO\BaseDTO;

class SberbankNotificationDTO extends BaseDTO
{
    public function __construct(
        private readonly string $mdOrder,
        private readonly string $orderNumber,
        private readonly string $operation,
        private readonly int $status,
        private readonly SberbankNotificationAdditionalDTO $additionalParams,
    ) {
    }

    public static function make(array $data): self
    {
        $additionalParams = SberbankNotificationAdditionalDTO::make(Arr::get($data, 'additionalParams') ?? []);

        return new self(
            Arr::get($data, 'mdOrder'),
            Arr::get($data, 'orderNumber'),
            Arr::get($data, 'operation'),
            Arr::get($data, 'status'),
            $additionalParams,
        );
    }

    public function getMdOrder(): string
    {
        return $this->mdOrder;
    }

    public function getOrderNumber(): string
    {
        return $this->orderNumber;
    }

    public function getOperation(): string
    {
        return $this->operation;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getAdditionalParams(): SberbankNotificationAdditionalDTO
    {
        return $this->additionalParams;
    }
}
