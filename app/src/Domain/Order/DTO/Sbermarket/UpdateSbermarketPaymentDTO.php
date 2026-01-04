<?php

namespace Domain\Order\DTO\Sbermarket;

use Illuminate\Support\Arr;
use Infrastructure\DTO\BaseDTO;

class UpdateSbermarketPaymentDTO extends BaseDTO
{
    public function __construct(
        private readonly string $mdOrder,
        private readonly string $checksum,
        private readonly string $operation,
        private readonly int $status,
        private readonly array $params,
    ) {
    }

    public static function make(array $data, array $params): self
    {
        return new self(
            Arr::get($data, 'mdOrder'),
            Arr::get($data, 'checksum'),
            Arr::get($data, 'operation'),
            Arr::get($data, 'status'),
            $params,
        );
    }

    public function getMdOrder(): string
    {
        return $this->mdOrder;
    }

    public function getChecksum(): string
    {
        return $this->checksum;
    }

    public function getOperation(): string
    {
        return $this->operation;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getParams(): array
    {
        return $this->params;
    }
}
