<?php

namespace Domain\User\Requests\Order;

use Domain\Order\Enums\Delivery\DeliveryTypeEnum;
use Domain\Order\Enums\OrderStateEnum;
use Domain\Order\Enums\OrderTypeEnum;
use Illuminate\Validation\Rule;
use Infrastructure\Http\Requests\PaginatedRequest;

class UserOrdersRequest extends PaginatedRequest
{
    public function rules(): array
    {
        $rules = parent::rules();

        return array_merge(
            $rules,
            [
                'requestFrom'   => [
                    'prohibits:deliveryType',
                    'string',
                    Rule::in(OrderTypeEnum::toValues()),
                ],
                'state'          => [
                    'string',
                    Rule::in(OrderStateEnum::toValues()),
                ],
                'deliveryType'   => [
                    'prohibits:requestFrom',
                    'string',
                    Rule::in(DeliveryTypeEnum::toValues()),
                ],
            ]
        );
    }
}
