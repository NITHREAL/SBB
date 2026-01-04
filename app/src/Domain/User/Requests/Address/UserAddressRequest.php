<?php

namespace Domain\User\Requests\Address;

use Domain\User\Enums\AddressEntranceVariantsEnum;
use Infrastructure\Http\Requests\BaseRequest;

class UserAddressRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'cityId'                => 'required|integer|exists:cities,id',
            'address'               => 'required|string',
            'cityName'              => 'string|nullable',
            'street'                => 'string|nullable',
            'house'                 => 'string|nullable',
            'building'              => 'string|nullable',
            'entrance'              => 'integer|nullable',
            'intercom'              => 'string|nullable',
            'apartment'             => 'integer|nullable',
            'floor'                 => 'integer|nullable',
            'comment'               => 'string|nullable',
            'otherCustomer'         => 'boolean|nullable',
            'otherCustomerPhone'    => [
                'required_if:otherCustomer,1',
                'string',
                'nullable',
                'regex:/^[0-9]{10}+$/',
            ],
            'otherCustomerName'     => 'required_if:otherCustomer,1|string|nullable',
            'hasNotIntercom'        => 'boolean',
            'entranceVariant'       => 'nullable|string|in:' . implode(',', AddressEntranceVariantsEnum::toValues()),
        ];
    }
}
