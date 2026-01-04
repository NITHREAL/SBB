<?php

namespace Domain\Order\Requests\Delivery;

use Domain\Order\Enums\Delivery\DeliveryTypeEnum;
use Illuminate\Validation\Rule;
use Infrastructure\Http\Requests\BaseRequest;

class AvailableDeliveryTypeRequest extends BaseRequest
{
    public function rules(): array
    {
        $delivery = DeliveryTypeEnum::delivery()->value;
        $pickup = DeliveryTypeEnum::pickup()->value;

        return [
            'deliveryType'     => ['required', Rule::in([$delivery, $pickup])],
            'cityId'           => ['required', 'integer', 'exists:cities,id'],
            'storeId'          => [
                "required_if:deliveryType,{$pickup}",
                'integer',
                'exists:stores,id'
            ],
            'address'           => [
                "required_if:deliveryType,{$delivery}",
                'string'
            ],
        ];
    }
}
