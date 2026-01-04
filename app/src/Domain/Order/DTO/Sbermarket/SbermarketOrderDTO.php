<?php

namespace Domain\Order\DTO\Sbermarket;

use Illuminate\Support\Arr;
use Infrastructure\DTO\BaseDTO;

class SbermarketOrderDTO extends BaseDTO
{
    public function __construct(
        private readonly string $eventType,
        private readonly ?string $sberOrderId,
        private readonly ?int $storeId,
        private readonly array $customerData,
        private readonly array $deliveryIntervalData,
        private readonly array $positions,
        private readonly array $totalData,
        private readonly array $addressData,
        private readonly ?string $comment,
        private readonly ?string $replacementPolicy,
        private readonly array $paymentMethods,
        private readonly ?string $shipmentMethod,
    ) {
    }

    public static function make(array $data): self
    {
        $payload = Arr::get($data, 'payload');

        return new self(
            Arr::get($data, 'event_type'),
            Arr::get($payload, 'originalOrderId'),
            Arr::get($payload, 'storeID'),
            Arr::get($payload, 'customer', []),
            Arr::get($payload, 'delivery', []),
            Arr::get($payload, 'positions', []),
            Arr::get($payload, 'total', []),
            Arr::get($payload, 'address', []),
            Arr::get($payload, 'comment'),
            Arr::get($payload, 'replacementPolicy'),
            Arr::get($payload, 'paymentMethod', []),
            Arr::get($payload, 'shipmentMethod'),
        );
    }

    public function getEventType(): string
    {
        return $this->eventType;
    }

    public function getSberOrderId(): ?string
    {
        return $this->sberOrderId;
    }

    public function getStoreId(): ?int
    {
        return $this->storeId;
    }

    public function getCustomerData(): array
    {
        return $this->customerData;
    }

    public function getDeliveryIntervalData(): array
    {
        return $this->deliveryIntervalData;
    }

    public function getPositions(): array
    {
        return $this->positions;
    }

    public function getTotalData(): array
    {
        return $this->totalData;
    }

    public function getAddressData(): array
    {
        return $this->addressData;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function getReplacementPolicy(): ?string
    {
        return $this->replacementPolicy;
    }

    public function getPaymentMethods(): array
    {
        return $this->paymentMethods;
    }

    public function getShipmentMethod(): ?string
    {
        return $this->shipmentMethod;
    }
}
