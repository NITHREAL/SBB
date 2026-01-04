<?php

namespace Domain\Store\Services\Exchange;

use Carbon\Carbon;
use Domain\Exchange\DTO\LeftoverDTO;
use Domain\Store\Models\ProductStore;
use Domain\Store\Models\Store;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Infrastructure\Enum\DaysOfWeek;

class LeftoverExchangeService
{
    public function exchange(LeftoverDTO $data): Model
    {
        /** @var Store $store */
        $store = Store::whereSystemId($data->systemId)->firstOrFail();
        $hashes = $this->prepareHashes($store->getAttribute('system_id'), $data->products);
        $leftovers = ProductStore::whereIn('hash', $hashes)->get();
        $products = array_map(fn($product) => $this->mergeWithExistingLeftover($leftovers, $product), $data->products);

        ProductStore::query()->upsert($products, ['hash']);

        return $store;
    }

    private function prepareHashes(string $storeSystemId, array &$products): array
    {
        $hashes = [];

        foreach ($products as &$product) {
            $product = $this->prepareData($storeSystemId, $product);
            $hashes[] = $product['hash'];
        }

        return $hashes;
    }

    private function prepareData(string $store1cId, array $leftover): array
    {
        $leftover['store_system_id'] = $store1cId;
        $leftover['product_system_id'] = $leftover['system_id'];
        $leftover['hash'] = ProductStore::makeHash($leftover['product_system_id'], $leftover['store_system_id']);

        unset($leftover['system_id']);

        if (isset($leftover['delivery_schedule'])) {
            $schedule = $this->convertDeliverySchedule($leftover['delivery_schedule']);
            $leftover['delivery_schedule'] = json_encode($schedule);
        }

        if (isset($leftover['discount_expires_in'])) {
            $leftover['discount_expires_in'] = Carbon::parse($leftover['discount_expires_in']);
        }

        return $leftover;
    }

    private function convertDeliverySchedule($exchangeDeliverySchedule): array
    {
        $days = DaysOfWeek::toArray();
        $deliverySchedule = [];

        foreach ($exchangeDeliverySchedule as $key) {
            $key = Str::ucfirst($key);

            if ($val = array_search($key, $days, true)) {
                $deliverySchedule[] = $val;
            }
        }

        return $deliverySchedule;
    }

    private function mergeWithExistingLeftover($leftovers, $product): array
    {
        $leftover = $leftovers->where('hash', $product['hash'])->first();

        $defaultData = [
            'active' => true,
            'price' => null,
            'price_discount' => null,
            'discount_expires_in' => null,
            'count' => 0,
            'delivery_schedule' => null
        ];

        return array_merge($defaultData, $leftover ? $leftover->toArray() : [], $product);
    }
}
