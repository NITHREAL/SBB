<?php

declare(strict_types=1);

namespace Domain\Order\DTO\Exchange;

use Illuminate\Support\Arr;
use Infrastructure\DTO\BaseDTO;

class OrderCreateExchangeDTO extends BaseDTO
{
    public function __construct(
        protected string $system_id,
        protected ?string $status,
        protected ?string $payment_type,
        protected ?string $delivery_type,
        protected ?string $delivery_price,
        protected ?string $delivery_service,
        protected ?string $delivery_cost,
        protected ?string $receive_date,
        protected ?string $receive_interval,
        protected ?string $comment,
        protected ?string $promo,
        protected ?array $contacts,
        protected ?array $products,
    ) {
    }

    public static function make(array $data): self
    {
        return new self(
            Arr::get($data, 'system_id'),
            Arr::get($data, 'status'),
            Arr::get($data, 'payment_type'),
            Arr::get($data, 'delivery_type'),
            Arr::get($data, 'delivery_price'),
            Arr::get($data, 'delivery_service'),
            Arr::get($data, 'delivery_cost'),
            Arr::get($data, 'receive_date'),
            Arr::get($data, 'receive_interval'),
            Arr::get($data, 'comment'),
            Arr::get($data, 'promo'),
            Arr::get($data, 'contacts'),
            Arr::get($data, 'products'),
        );
    }

    public function getSystemId(): string
    {
        return $this->system_id;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function getPaymentType(): ?string
    {
        return $this->payment_type;
    }

    public function getDeliveryType(): ?string
    {
        return $this->delivery_type;
    }

    public function getDeliveryPrice(): ?string
    {
        return $this->delivery_price;
    }

    public function getDeliveryService(): ?string
    {
        return $this->delivery_service;
    }

    public function getDeliveryCost(): ?string
    {
        return $this->delivery_cost;
    }

    public function getReceiveDate(): ?string
    {
        return $this->receive_date;
    }

    public function getReceiveInterval(): ?string
    {
        return $this->receive_interval;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function getPromo(): ?string
    {
        return $this->promo;
    }

    public function getContacts(): ?array
    {
        return $this->contacts;
    }

    public function getProducts(): ?array
    {
        return $this->products;
    }
}
