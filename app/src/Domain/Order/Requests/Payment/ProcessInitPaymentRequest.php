<?php

namespace Domain\Order\Requests\Payment;

use Infrastructure\Http\Requests\BaseRequest;

class ProcessInitPaymentRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'orderSystemId'   => 'required|string',
        ];
    }
}
