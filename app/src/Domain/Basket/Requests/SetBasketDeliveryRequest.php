<?php

namespace Domain\Basket\Requests;

use Domain\Order\Enums\Delivery\DeliveryTypeEnum;
use Domain\Order\Helpers\Delivery\OrderDeliveryHelper;
use Illuminate\Validation\Rule;
use Infrastructure\Http\Requests\BaseRequest;

class SetBasketDeliveryRequest extends BaseRequest
{
    public function rules(): array
    {
        $delivery = DeliveryTypeEnum::delivery()->value;
        $pickup = DeliveryTypeEnum::pickup()->value;

        return [
            'cityId'                                   => [
                'required',
                'integer',
                'exists:cities,id',
            ],
            'deliveryParams'                           => 'array',
            'deliveryParams.*'                         => 'required_with:deliveryParams|array',
            'deliveryParams.*.date'                    => [
                'required_with:deliveryParams',
                'string',
                'date_format:Y-m-d',
                'after:yesterday',
            ],
            'deliveryParams.*.time'                    => [
                'required_with:deliveryParams',
                'string',
            ],
            'deliveryParams.*.deliveryType'           => [
                'required_with:deliveryParams',
                'string',
                Rule::in(OrderDeliveryHelper::getDeliveryTypes()),
            ],
            'deliveryParams.*.deliverySubType'       => [
                'required_with:deliveryParams',
                'string',
                Rule::in(OrderDeliveryHelper::getDeliverySubTypes()),
            ],
            'deliveryParams.*.storeId'                => [
                "required_if:deliveryType,{$pickup}",
                'integer',
                'exists:stores,id'
            ],
            'deliveryParams.*.address'           => [
                "required_if:deliveryType,{$delivery}",
                'string'
            ],
        ];
    }
}
