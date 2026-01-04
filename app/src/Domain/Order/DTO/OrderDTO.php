<?php

namespace Domain\Order\DTO;

use Domain\Order\Helpers\OrderHelper;
use Illuminate\Support\Arr;
use Infrastructure\DTO\BaseDTO;

class OrderDTO extends BaseDTO
{
    public function __construct(
        private readonly string  $paymentType,
        private readonly ?int    $bindingId,
        private readonly ?string $comment,
        private readonly array   $utm,
        private readonly string  $source,
        private readonly bool    $electronicChecks,
        private readonly array   $delivery,
        private readonly ?string  $payerIp,
    ) {
    }

    public static function make(array $data): self
    {
        $delivery = self::getDeliveryData(Arr::get($data, 'delivery', []));

        return new self(
            Arr::get($data, 'paymentType'),
            Arr::get($data, 'bindingId'),
            Arr::get($data, 'comment'),
            Arr::get($data, 'utm', []),
            Arr::get($data, 'source'),
            Arr::get($data, 'electronicChecks', false),
            $delivery,
            Arr::get($data, 'payerIp'),
        );
    }

    public function getPaymentType(): string
    {
        return $this->paymentType;
    }

    public function getBindingId(): ?int
    {
        return $this->bindingId;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function getUtm(): array
    {
        return $this->utm;
    }

    public function getSource(): string
    {
        return $this->source;
    }

    public function getDelivery(): array
    {
        return $this->delivery;
    }

    public function getElectronicChecks(): bool
    {
        return $this->electronicChecks;
    }

    public function getPayerIp(): ?string
    {
        return $this->payerIp;
    }

    private static function getDeliveryData(array $data): array
    {
        $result = [];

        foreach ($data as $deliveryParams) {
            $result[] = OrderDeliveryDTO::make($deliveryParams);
        }

        return $result;
    }
}
