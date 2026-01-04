<?php

namespace Infrastructure\Services\Buyer\Components\Store;

use Domain\Store\Models\Store;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Infrastructure\Services\Buyer\BuyerDataService;
use Infrastructure\Services\Buyer\Components\Store\Helper\StoreEntityHelper;
use Infrastructure\Services\Buyer\Facades\BuyerCity;

class BuyerStoreService extends BuyerDataService
{
    protected const ATTRIBUTE_CACHE_KEY = 'store';

    private const ATTRIBUTE_STORE_ID = 'id';

    private const ATTRIBUTE_STORE_ONE_C_ID = 'system_id';

    private const ATTRIBUTE_STORE_TITLE = 'title';

    private const ATTRIBUTE_STORE_CITY_ID = 'city_id';

    private const ATTRIBUTE_STORE_ADDRESS = 'address';

    private const ATTRIBUTE_STORE_LATITUDE = 'latitude';

    private const ATTRIBUTE_STORE_LONGITUDE = 'longitude';

    protected StoreEntityHelper $storeEntityHelper;

    public function __construct() {
        parent::__construct();

        $this->storeEntityHelper = app()->make(StoreEntityHelper::class);
    }

    public function setValue(array|string $value): void
    {
        if (is_string($value)) {
            $value = $this->getStoreData($value);
        } else {
            $value = array_filter(
                $value,
                function ($item, $key) {
                    return is_string($item) && in_array($key, $this->getAvailableAttributeKeys());
                },
                ARRAY_FILTER_USE_BOTH,
            );
        }

        if (count($value)) {
            $this->setCachedValue($value);
        }
    }

    public function getSelectedStore(): ?Store
    {
        $storeId = $this->getId();

        return Store::find($storeId);
    }

    public function getId(array $storeData = null): int
    {
        return $this->getStoreAttribute(self::ATTRIBUTE_STORE_ID, $storeData);
    }

    public function getOneCId(array $storeData = null): string
    {
        return $this->getStoreAttribute(self::ATTRIBUTE_STORE_ONE_C_ID, $storeData);
    }

    public function getTitle(array $storeData = null): string
    {
        return $this->getStoreAttribute(self::ATTRIBUTE_STORE_TITLE, $storeData);
    }

    public function getCityId(array $storeData = null): int
    {
        return $this->getStoreAttribute(self::ATTRIBUTE_STORE_CITY_ID, $storeData);
    }

    public function getAddress(array $storeData = null): string
    {
        return $this->getStoreAttribute(self::ATTRIBUTE_STORE_ADDRESS, $storeData);
    }

    public function getLatitude(array $storeData = null): string
    {
        return $this->getStoreAttribute(self::ATTRIBUTE_STORE_LATITUDE, $storeData);
    }

    public function getLongitude(array $storeData = null): string
    {
        return $this->getStoreAttribute(self::ATTRIBUTE_STORE_LONGITUDE, $storeData);
    }

    protected function getDefaultValue(): array
    {
        $cityId = BuyerCity::getValue();

        $store = $this->storeEntityHelper->getDefaultUserStore($cityId, Auth::id()) ?? Store::first();

        return $this->getPreparedStoreData($store);
    }

    protected function getStoreData(string $storeId): array
    {
        if ($store = Store::find($storeId)) {
            $data = $this->getPreparedStoreData($store);
        } else {
            $data = $this->getDefaultValue();
        }

        return $data;
    }

    private function getPreparedStoreData(Store $store): array
    {
        return [
            self::ATTRIBUTE_STORE_ID        => $store->getAttribute('id'),
            self::ATTRIBUTE_STORE_ONE_C_ID  => $store->getAttribute('system_id'),
            self::ATTRIBUTE_STORE_TITLE     => $store->getAttribute('title'),
            self::ATTRIBUTE_STORE_CITY_ID   => $store->getAttribute('city_id'),
            self::ATTRIBUTE_STORE_ADDRESS   => $store->getAttribute('address'),
            self::ATTRIBUTE_STORE_LATITUDE  => $store->getAttribute('latitude'),
            self::ATTRIBUTE_STORE_LONGITUDE => $store->getAttribute('longitude'),
        ];
    }

    private function getStoreAttribute(string $attributeKey, ?array $storeData = null)
    {
        $storeData = $storeData ?? $this->getValue();

        return Arr::get($storeData, $attributeKey);
    }

    private function getAvailableAttributeKeys(): array
    {
        return [
            self::ATTRIBUTE_STORE_ID,
            self::ATTRIBUTE_STORE_ONE_C_ID,
            self::ATTRIBUTE_STORE_TITLE,
            self::ATTRIBUTE_STORE_CITY_ID,
            self::ATTRIBUTE_STORE_ADDRESS,
            self::ATTRIBUTE_STORE_LATITUDE,
            self::ATTRIBUTE_STORE_LONGITUDE,
        ];
    }
}
