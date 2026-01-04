<?php

namespace Infrastructure\Http\Resources\DaData;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class DaDataCityResource extends JsonResource
{
    public function toArray($request): array
    {
        $item = $this->resource['data'];

        return [
            'title'         => Arr::get($item, 'city') ?? Arr::get($item, 'settlement'),
            'fullTitle'     => Arr::get($this->resource, 'value'),
            'type'          => Arr::get($item, 'settlement_type') ?? Arr::get($item, 'city_type'),
            'region'        => Arr::get($item, 'region_with_type'),
            'cityId'        => Arr::get($item, 'city_id'),
            'fiasId'        => Arr::get($item, 'city_fias_id') ?? Arr::get($item, 'settlement_fias_id'),
            'isSettlement'  => empty(Arr::get($item, 'city_fias_id')) && Arr::get($item, 'settlement_fias_id'),
            'latitude'      => (float) Arr::get($item, 'geo_lat'),
            'longitude'     => (float) Arr::get($item, 'geo_lon'),
        ];
    }
}
