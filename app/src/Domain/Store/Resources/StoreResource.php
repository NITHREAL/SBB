<?php

declare(strict_types=1);

namespace Domain\Store\Resources;

use Domain\Order\Enums\Delivery\PolygonDeliveryTypeEnum;
use Domain\Order\Resources\Polygon\PolygonResource;
use Domain\Store\Helpers\StoreHelper;
use Illuminate\Http\Resources\Json\JsonResource;

class StoreResource extends JsonResource
{
    public function toArray($request): array
    {
        $store = $this->resource;

        // Для клиентской части передаем только полигон обычной доставки
        $polygon = $this->polygons?->where('type', PolygonDeliveryTypeEnum::extended()->value)->first();

        return [
            'id'            => $store->id,
            'title'         => $store->title,
            'slug'          => $store->slug,
            'cityId'        => $store->city_id,
            'address'       => StoreHelper::getStorePreparedAddress($store->cityTitle, $store->title),
            'workTime'      => $store->work_time,
            'opened'        => $store->isOpened,
            'latitude'      => $store->latitude,
            'longitude'     => $store->longitude,
            'contacts'      => $store->contacts ? StoreContactResource::collection($store->contacts) : [],
            'polygon'       => $store ? PolygonResource::make($polygon) : null,
        ];
    }

    public function withResponse($request, $response): void
    {
        $meta = $response->addMeta([
            'includes' => [
                'contacts' => StoreContactResource::class,
                'polygons' => PolygonResource::class,
            ],
        ]);

        $response->setData(array_merge($response->getData(true), ['meta' => $meta]));
    }
}
