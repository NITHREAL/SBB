<?php

namespace Domain\User\Resources\Store;

use Domain\Store\Resources\StoreResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class UserStoresResource extends JsonResource
{
    public function toArray($request): array
    {
        $stores = Arr::get($this, 'stores');

        return [
            'userId'    => Arr::get($this, 'userId'),
            'stores'    => $stores
                ? StoreResource::collection($stores)
                : [],
        ];
    }
}
