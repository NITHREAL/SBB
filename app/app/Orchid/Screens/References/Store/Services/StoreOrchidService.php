<?php

namespace App\Orchid\Screens\References\Store\Services;

use Domain\City\Models\City;
use Domain\Order\Models\Delivery\Polygon;
use Domain\Order\Models\Delivery\PolygonDeliveryPrice;
use Domain\Store\Models\Store;
use Domain\Store\Models\StoreContact;
use Domain\Store\Requests\Admin\StoreRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class StoreOrchidService
{
    /**
     * @param Store $store
     * @param StoreRequest $request
     * @return void
     */
    public function saveStore(Store $store, StoreRequest $request): void
    {
        DB::transaction(function () use ($store, $request) {
            $storeData = $request->validated()['store'];
            $this->associateCityWithStore($store, $storeData['city_id']);
            $this->updateSchedule($store, $storeData);
            $store->update($storeData);

            $this->syncContacts($store, $storeData['contacts'] ?? []);
            $this->handlePolygons($store, collect($request->get('polygons', [])));
            $this->syncPaymentTypes($store, $storeData);

        });
    }

    /**
     * @param Store $store
     * @param array $storeData
     * @return void
     */
    private function updateSchedule(Store $store, array $storeData): void
    {
        $this->updateSheduleWeekDays($store, collect(Arr::get($storeData, 'scheduleWeekdays', [])));
        $this->updateSheduleDates($store, collect(Arr::get($storeData, 'scheduleDates', [])));
    }

    /**
     * связывания магазин с городом
     * @param Store $store
     * @param $cityId
     * @return void
     */
    private function associateCityWithStore(Store $store, $cityId): void
    {
        $city = City::query()->findOrFail($cityId);
        $store->city()->associate($city);
    }

    /**
     * @param Store $store
     * @param Collection $polygonsData
     * @return void
     * @throws \JsonException
     */
    private function handlePolygons(Store $store, Collection $polygonsData): void
    {
        $currentPolygons = $store->polygons->keyBy('id');

        $polygonsData->each(function ($polygonData) use ($currentPolygons, $store) {
            $polygon = json_decode($polygonData, true, 512, JSON_THROW_ON_ERROR);
            $coordinates = Arr::get($polygon, 'coordinates');

            if ($coordinates) {
                $this->handleSinglePolygon($store, $currentPolygons, $polygon);
            }
        });
        $this->deleteOldPolygons($currentPolygons);
    }

    /**
     * @param Store $store
     * @param Collection $currentPolygons
     * @param array $polygon
     * @return void
     */
    private function handleSinglePolygon(Store $store, Collection $currentPolygons, array $polygon): void
    {
        $polygonId = $polygon['id'] ?? null;

        if ($polygonId && $currentPolygons->has($polygonId)) {
            $this->updatePolygon($currentPolygons->get($polygonId), $polygon);
            $currentPolygons->forget($polygonId);
        } else {
            $this->createPolygon($store, $polygon);
        }
    }

    /**
     * @param Polygon $polygon
     * @param array $data
     * @return void
     */
    private function updatePolygon(Polygon $polygon, array $data): void
    {
        $polygon->update(Arr::only($data, ['coordinates', 'fill_color', 'stroke_color', 'type']));
        $this->saveDeliveryPricesForPolygon($polygon, $data['delivery_prices'] ?? []);
    }

    /**
     * @param Store $store
     * @param array $data
     * @return void
     */
    private function createPolygon(Store $store, array $data): void
    {
        $polygon = Polygon::create(array_merge(
            Arr::only($data, ['coordinates', 'fill_color', 'stroke_color', 'type']),
            ['store_system_id' => $store->getAttribute('system_id')]
        ));
        $this->saveDeliveryPricesForPolygon($polygon, $data['delivery_prices'] ?? []);
    }

    /**
     * @param Collection $currentPolygons
     * @return void
     */
    private function deleteOldPolygons(Collection $currentPolygons): void
    {
        if ($currentPolygons->isNotEmpty()) {
            Polygon::whereIn('id', $currentPolygons->keys())->delete();
        }
    }


    /**
     * @param Polygon $polygon
     * @param array $deliveryPricesData
     * @return void
     */
    private function saveDeliveryPricesForPolygon(Polygon $polygon, array $deliveryPricesData): void
    {
        $currentDeliveryPrices = $polygon->deliveryPrices->keyBy('id')->toArray();

        foreach ($deliveryPricesData as $newDeliveryPrice) {
            $id = Arr::get($newDeliveryPrice, 'id'); // Предположим, что идентификатор передается как 'id'
            $from = Arr::get($newDeliveryPrice, 'from');
            $to = Arr::get($newDeliveryPrice, 'to');
            $price = Arr::get($newDeliveryPrice, 'price');

            if (isset($currentDeliveryPrices[$id])) {
                PolygonDeliveryPrice::query()->where('id', $id)->update([
                    'from'  => $from,
                    'to'    => $to,
                    'price' => $price,
                ]);
                unset($currentDeliveryPrices[$id]);
            } else {
                $polygon->deliveryPrices()->create([
                    'from'  => $from,
                    'to'    => $to,
                    'price' => $price,
                ]);
            }
        }
        if (!empty($currentDeliveryPrices)) {
            PolygonDeliveryPrice::query()->whereIn('id', array_keys($currentDeliveryPrices))->delete();
        }
    }

    /**
     * @param Store $store
     * @param array $data
     * @return void
     */
    private function syncPaymentTypes(Store $store, array $data): void
    {
        $paymentTypes = collect(Arr::get($data, 'payments', []));

        if (!Arr::get($data, 'payments_from_city')) {
            $store->payments()->sync($paymentTypes->pluck('id'));
        }
    }

    /**
     * @param Store $store
     * @param array $contacts
     * @return void
     */
    private function syncContacts(Store $store, array $contacts): void
    {
        $storedIds = $store->contacts->pluck('id')->toArray();

        foreach ($contacts as $contact) {
            if (!isset($contact['id'])) {

                $store->contacts()->create($contact);
            } else {
                $store->contacts()->updateOrCreate([
                    'id' => $contact['id']
                ], $contact);
            }
        }

        $receivedIds = array_column($contacts, 'id');
        $removedIds = array_diff($storedIds, $receivedIds);

        StoreContact::whereIn('id', $removedIds)->delete();
    }

    /**
     * @param $store
     * @param $scheduleWeek
     * @return void
     */
    private function updateSheduleWeekDays($store, $scheduleWeek): void
    {
        $currentIds = $store->scheduleWeekdays()->pluck('id')->toArray();

        foreach ($scheduleWeek as $item) {
            $itemId = $item['id'] ?? null;

            if (in_array($itemId, $currentIds)) {
                $store->scheduleWeekdays()->where('id', $itemId)->update($item);
                $currentIds = array_diff($currentIds, [$itemId]);
            } else {
                $store->scheduleWeekdays()->create($item);
            }
        }
        $store->scheduleWeekdays()->whereIn('id', $currentIds)->delete();
    }

    /**
     * @param $store
     * @param $scheduleDates
     * @return void
     */
    private function updateSheduleDates($store, $scheduleDates): void
    {
        $currentIds = $store->scheduleDates()->pluck('id')->toArray();

        foreach ($scheduleDates as $item) {
            $itemId = $item['id'] ?? null;

            if (in_array($itemId, $currentIds)) {
                $store->scheduleDates()->where('id', $itemId)->update($item);
                $currentIds = array_diff($currentIds, [$itemId]);
            } else {
                $store->scheduleDates()->create($item);
            }
        }
        $store->scheduleWeekdays()->whereIn('id', $currentIds)->delete();
    }
}
