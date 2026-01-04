<?php

namespace Domain\Exchange\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Infrastructure\Enum\DaysOfWeek;

class ProductStoreResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'system_id' => $this->product_system_id,
            'active' => (bool)$this->active,
            'price' => (float)$this->price,
            'price_discount' => (float)$this->price_discount,
            'discount_expires_in' => (int)$this->discount_expires_in?->getTimestamp(),
            'count' => (float)$this->count,
            'delivery_schedule' => is_array($this->delivery_schedule) ?
                $this->convertDates($this->delivery_schedule) :
                []
        ];
    }

    private function convertDates(array $deliveryDays): array
    {
        $daysOfWeek = DaysOfWeek::toArray();

        $result = [];

        foreach ($deliveryDays as $deliveryDay) {
            if (isset($daysOfWeek[$deliveryDay])) {
                $result[] = $daysOfWeek[$deliveryDay];
            }
        }

        return $result;
    }
}
