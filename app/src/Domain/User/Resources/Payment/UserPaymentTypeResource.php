<?php

namespace Domain\User\Resources\Payment;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class UserPaymentTypeResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'title'     => Arr::get($this->resource, 'title'),
            'value'     => Arr::get($this->resource, 'value'),
            'isDefault' => (bool) Arr::get($this->resource, 'is_default'),
        ];
    }
}
