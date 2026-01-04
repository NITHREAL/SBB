<?php

namespace Domain\Order\Requests\Delivery;

use Domain\Order\Helpers\Delivery\OrderDeliveryHelper;
use Illuminate\Validation\Rule;
use Infrastructure\Http\Requests\BaseRequest;

class DeliveryDateTimeTypeRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'date'              => 'required|string|date_format:Y-m-d|after:yesterday',
            'time'              => 'required|string',
            'deliverySubType'   => [
                Rule::in(OrderDeliveryHelper::getDeliverySubTypes()),
                'required',
            ],
        ];
    }
}
