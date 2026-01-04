<?php

namespace Domain\Basket\DTO;

use Illuminate\Support\Arr;
use Infrastructure\DTO\BaseDTO;

class BasketSettingDTO extends BaseDTO
{
    public function __construct(
        private readonly string  $unavailableSettingValue,
        private readonly string  $weightSettingValue,
        private readonly bool    $orderForOtherPersonValue,
        private readonly string  $checkType,
        private readonly ?string $otherPersonPhone,
        private readonly ?string $otherPersonName
    ) {
    }

    public static function make(array $data): self
    {
        return new self(
            Arr::get($data, 'unavailableSettingValue'),
            Arr::get($data, 'weightSettingValue'),
            Arr::get($data, 'orderForOtherPersonValue', false),
            Arr::get($data, 'checkType'),
            Arr::get($data, 'otherPersonPhone'),
            Arr::get($data, 'otherPersonName')
        );
    }

    public function getUnavailableSettingValue(): string
    {
        return $this->unavailableSettingValue;
    }

    public function getWeightSettingValue(): string
    {
        return $this->weightSettingValue;
    }

    public function getOrderForOtherPersonValue(): bool
    {
        return $this->orderForOtherPersonValue;
    }

    public function getCheckType(): string
    {
        return $this->checkType;
    }

    public function getOtherPersonPhone(): ?string
    {
        return $this->otherPersonPhone;
    }

    public function getOtherPersonName(): ?string
    {
        return $this->otherPersonName;
    }

    public function getSettings(): array
    {
        return [
            'unavailable_settings'              => $this->unavailableSettingValue,
            'weight_settings'                   => $this->weightSettingValue,
            'order_for_other_person_settings'   => $this->orderForOtherPersonValue,
            'check_type'                        => $this->checkType,
            'other_person_phone'                => $this->otherPersonPhone,
            'other_person_name'                 => $this->otherPersonName
        ];
    }
}
