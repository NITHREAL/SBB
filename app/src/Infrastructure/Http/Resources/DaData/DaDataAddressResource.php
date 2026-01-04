<?php

namespace Infrastructure\Http\Resources\DaData;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class DaDataAddressResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'cityId'   => (int) Arr::get($this, 'city_id'),
            'city'      => (string) Arr::get($this, 'location'),
            'street'    => (string) Arr::get($this, 'street'),
            'house'     => (string) Arr::get($this, 'house'),
            'value'     => (string) Arr::get($this, 'value'),
            'latitude'  => (string) Arr::get($this, 'latitude'),
            'longitude' => (string) Arr::get($this, 'longitude'),
        ];
    }
}
