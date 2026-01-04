<?php

namespace Domain\Order\Requests\Delivery;

use Domain\Order\Helpers\Delivery\OrderDeliveryHelper;
use Illuminate\Validation\Rule;
use Infrastructure\Http\Requests\BaseRequest;

class DefaultDeliveryTypeRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'cityId'       => ['integer'],
            'deliveryType' => ['string', Rule::in(OrderDeliveryHelper::getDeliveryTypes())],
        ];
    }
}
