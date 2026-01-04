<?php

namespace Domain\User\Resources\Payment;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class UserPaymentMethodsResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'types'     => UserPaymentTypeResource::collection(Arr::get($this->resource, 'types')),
            'bindings'  => UserPaymentBindingResource::collection(Arr::get($this->resource, 'bindings')),
        ];
    }
}
