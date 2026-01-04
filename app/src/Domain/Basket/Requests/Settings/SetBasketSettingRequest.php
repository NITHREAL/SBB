<?php

namespace Domain\Basket\Requests\Settings;

use Domain\Order\Enums\OrderSetting\CheckTypeProductOrderSettingEnum;
use Domain\Order\Enums\OrderSetting\UnavailableProductOrderSettingEnum;
use Domain\Order\Enums\OrderSetting\WeightProductOrderSettingEnum;
use Illuminate\Validation\Rule;
use Infrastructure\Http\Requests\BaseRequest;

class SetBasketSettingRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'unavailableSettingValue' => [
                'required',
                'string',
                Rule::in(UnavailableProductOrderSettingEnum::toValues())
            ],

            'weightSettingValue' => [
                'required',
                'string',
                Rule::in(WeightProductOrderSettingEnum::toValues())
            ],

            'orderForOtherPersonValue' => [
                'sometimes',
                'bool'
            ],

            'checkType' => [
                'required',
                'string',
                Rule::in(CheckTypeProductOrderSettingEnum::toValues())
            ],

            'otherPersonPhone' => [
                'required_if:orderForOtherPersonValue,true',
                'nullable',
                'string',
            ],

            'otherPersonName' => [
                'required_if:orderForOtherPersonValue,true',
                'nullable',
                'string'
            ],
        ];
    }
}
