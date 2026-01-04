<?php

namespace Domain\Order\Requests\Admin\PaymentType;

use Domain\Order\Enums\Delivery\DeliveryTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PaymentTypeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'payment.title' => 'required|string',
            'payment.active' => 'required|boolean',
            'payment.sort' => 'required|integer|min:0',
            'payment.delivery_type' => 'required|array',
            'payment.delivery_type.*' => ['string', Rule::in(DeliveryTypeEnum::toValues())],
            'payment.for_all_cities' => 'required|boolean',
            'payment.cities' => 'array',
            'payment.cities.*.id' => 'exists:cities,id'
        ];
    }
}
