<?php

namespace Domain\User\Resources\Store;

use Domain\Store\Resources\StoreResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class UserStoreResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'userId'    => Arr::get($this, 'userId'),
            'store'     => StoreResource::make(Arr::get($this, 'store')),
        ];
    }
}
