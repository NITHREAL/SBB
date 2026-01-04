<?php

namespace Domain\Order\Requests\Sbermarket;

use Infrastructure\Http\Requests\BaseRequest;

class SbermarketWebhookRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'event_type'                        => 'string',
            'payload.originalOrderId'           => 'string',
            'payload.store_id'                  => 'integer',
            'payload.storeID'                   => 'integer',
            'payload.customer'                  => 'array',
            'payload.customer.name'             => 'string|nullable',
            'payload.customer.phone'            => 'string',
            'payload.delivery.expectedFrom'     => 'string',
            'payload.delivery.expectedTo'       => 'string',
            'payload.positions'                 => 'array',
            'payload.total'                     => 'array',
            'payload.total.totalPrice'          => 'string',
            'payload.total.discountTotalPrice'  => 'string',
            'payload.comment'                   => 'string|nullable',
            'payload.replacementPolicy'         => 'string',
            'payload.paymentMethods'            => 'array',
            'payload.shipmentMethod'            => 'string',
        ];
    }
}
