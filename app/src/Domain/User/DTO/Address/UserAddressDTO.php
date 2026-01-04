<?php

namespace Domain\User\DTO\Address;

use Domain\City\Models\City;
use Domain\User\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Arr;
use Infrastructure\DTO\BaseDTO;

class UserAddressDTO extends BaseDTO
{
    public function __construct(
        private int $cityId,
        private string $address,
        private ?string $cityName,
        private ?string $street,
        private ?string $house,
        private ?string $building,
        private ?int $entrance,
        private ?string $intercom,
        private ?int $apartment,
        private ?int $floor,
        private ?string $comment,
        private ?bool $otherCustomer,
        private ?string $otherCustomerPhone,
        private ?string $otherCustomerName,
        private User|Authenticatable $user,
        public ?bool $isMain,
        public bool $hasNotIntercom,
        public ?string $entranceVariant,
    ) {
    }

    public static function make(array $data, User $user): self
    {
        return new self(
            Arr::get($data, 'cityId'),
            Arr::get($data, 'address'),
            Arr::get($data, 'cityName'),
            Arr::get($data, 'street'),
            Arr::get($data, 'house'),
            Arr::get($data, 'building'),
            Arr::get($data, 'entrance'),
            Arr::get($data, 'intercom'),
            Arr::get($data, 'apartment'),
            Arr::get($data, 'floor'),
            Arr::get($data, 'comment'),
            Arr::get($data, 'otherCustomer', false),
            Arr::get($data, 'otherCustomerPhone'),
            Arr::get($data, 'otherCustomerName'),
            $user,
            Arr::get($data, 'isMain', true),
            Arr::get($data, 'hasNotIntercom') ?? false,
            Arr::get($data, 'entranceVariant'),
        );
    }

    public function getCity(): City
    {
        return City::findOrFail($this->cityId);
    }

    public function getCityName(): ?string
    {
        $value = $this->cityName;

        if (empty($value)) {
            $value = Arr::get($this->getAddressData(), 0);
        }

        return $value;
    }

    public function getStreet(): ?string
    {
        $value = $this->street;

        if (empty($value)) {
            $addressData = $this->getAddressData();

            $value = count($addressData) > 2
                ? Arr::get($addressData, 1)
                : null;
        }

        return $value;
    }

    public function getHouse(): ?string
    {
        $value = $this->house;

        if (empty($value)) {
            $addressData = $this->getAddressData();

            $value = count($addressData) > 2
                ? Arr::get($addressData, 2)
                : Arr::get($addressData, 1);
        }

        return $value;
    }

    public function getAddress(): string
    {
        $value = $this->address;

        if (empty($value)) {
            $value = sprintf(
                '%s, %s, %s',
                $this->getCityName(),
                $this->getStreet(),
                $this->getHouse(),
            );
        }

        return $value;
    }

    public function getBuilding(): ?string
    {
        return $this->building;
    }

    public function getEntrance(): ?string
    {
        return $this->entrance;
    }

    public function getIntercom(): ?string
    {
        return $this->intercom;
    }

    public function getApartment(): ?string
    {
        return $this->apartment;
    }

    public function getFloor(): ?string
    {
        return $this->floor;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function getIsOtherCustomer(): bool
    {
        return $this->otherCustomer ?? false;
    }

    public function getOtherCustomerPhone(): ?string
    {
        return $this->otherCustomerPhone;
    }

    public function getOtherCustomerName(): ?string
    {
        return $this->otherCustomerName;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getIsMain(): bool
    {
        return $this->isMain;
    }

    private function getAddressData(): array
    {
        return explode(', ', $this->address);
    }

    public function getHasNotIntercom(): bool
    {
        return $this->hasNotIntercom;
    }

    public function getEntranceVariant(): ?string
    {
        return $this->entranceVariant;
    }
}
