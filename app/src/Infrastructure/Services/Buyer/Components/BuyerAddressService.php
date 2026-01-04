<?php

namespace Infrastructure\Services\Buyer\Components;

use Domain\Order\Helpers\Delivery\OrderDeliveryHelper;
use Domain\User\Models\UserAddress;
use Illuminate\Support\Facades\Auth;
use Infrastructure\Services\Buyer\BuyerDataService;
use Infrastructure\Services\Buyer\Facades\BuyerCity;
use Infrastructure\Services\Buyer\Facades\BuyerDeliveryType;
use Infrastructure\Services\Buyer\Facades\BuyerStore;

class BuyerAddressService extends BuyerDataService
{
    protected const ATTRIBUTE_CACHE_KEY = 'address';

    protected function getDefaultValue(): ?string
    {
        $userId = Auth::id();

        if (OrderDeliveryHelper::isDelivery(BuyerDeliveryType::getValue())) {
            $address = $userId
                ? $this->getDefaultUserAddress($userId)
                : null;
        } else {
            $address = BuyerStore::getTitle();
        }

        return $address;
    }

    private function getDefaultUserAddress(int $userId): ?string
    {
        $cityId = BuyerCity::getValue();

        $userAddresses = UserAddress::query()
            ->whereUser($userId)
            ->whereCity($cityId)
            ->orderByDesc('user_addresses.updated_at')
            ->get();

        $userAddress = $userAddresses->where('chosen', true)->first() ?? $userAddresses->first();

        return $userAddress?->address;
    }
}
