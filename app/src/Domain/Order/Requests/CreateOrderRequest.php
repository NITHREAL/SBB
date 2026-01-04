<?php

namespace Domain\Order\Requests;

use Domain\Order\Enums\OrderSourceEnum;
use Domain\Order\Enums\Payment\PaymentTypeEnum;
use Domain\Order\Helpers\Delivery\OrderDeliveryHelper;
use Illuminate\Validation\Rule;
use Infrastructure\Http\Requests\BaseRequest;

class CreateOrderRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'paymentType'                           => ['required', 'string', Rule::in(PaymentTypeEnum::toValues())],
            'source'                                => ['required', 'string', Rule::in(OrderSourceEnum::toValues())],
            'comment'                               => ['nullable', 'string'],
            'bindingId'                             => ['nullable', 'integer'],
            'utm'                                   => ['nullable', 'array'],
            'utm.*.utmSource'                       => ['nullable', 'string'],
            'utm.*.utmMedium'                       => ['nullable', 'string'],
            'utm.*.utmCampaign'                     => ['nullable', 'string'],
            'utm.*.utmTerm'                         => ['nullable', 'string'],
            'utm.*.utmContent'                      => ['nullable', 'string'],
            'delivery'                              => ['required', 'array'],
            'delivery.*'                            => ['required_with:delivery', 'array'],
            'delivery.*.deliveryType'               => ['required', 'string', Rule::in(OrderDeliveryHelper::getDeliveryTypes())],
            'delivery.*.deliverySubType'            => ['required', 'string', Rule::in(OrderDeliveryHelper::getDeliverySubTypes())],
            'delivery.*.deliveryDate'               => ['required', 'string', 'date_format:Y-m-d', 'after:yesterday'],
            'delivery.*.deliveryTime'               => ['required', 'string'], //TODO добавить валидацию для формата времени
            'delivery.*.deliveryService'            => ['nullable', 'string'],
            'delivery.*.storeOneCId'                => ['required', 'string'],
            'delivery.*.cityId'                     => ['required', 'integer', 'exists:cities,id'],
            'delivery.*.address'                    => ['required', 'string'],
            'electronicChecks'                      => ['nullable', 'boolean'],
            'emailForCheck'                         => ['nullable', 'string'],
        ];
    }
}
