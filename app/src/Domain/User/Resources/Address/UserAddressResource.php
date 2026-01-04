<?php

namespace Domain\User\Resources\Address;

use Illuminate\Http\Resources\Json\JsonResource;

class UserAddressResource extends JsonResource
{
    public function toArray($request): array
    {
        $userAddress = $this->resource;

        return [
            'id'                    => $userAddress->id,
            'userId'                => $userAddress->user_id,
            'address'               => (string) $userAddress->address,
            'cityName'              => $userAddress->city_name,
            'street'                => $userAddress->street,
            'house'                 => $userAddress->house,
            'building'              => $userAddress->building,
            'apartment'             => $userAddress->apartment,
            'entrance'              => $userAddress->entrance,
            'intercom'              => $userAddress->intercom,
            'floor'                 => $userAddress->floor,
            'comment'               => $userAddress->comment,
            'otherCustomer'         => (bool) $userAddress->other_customer,
            'otherCustomerPhone'    => $userAddress->other_customer_phone,
            'otherCustomerName'     => $userAddress->other_customer_name,
            'cityId'                => $userAddress->city_id,
            'hasNotIntercom'        => (bool) $userAddress->has_not_intercom,
            'entranceVariant'       => $userAddress->entrance_variant,
        ];
    }
}
