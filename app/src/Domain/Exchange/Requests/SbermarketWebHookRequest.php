<?php

namespace Domain\Exchange\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SbermarketWebHookRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'event_type' => 'string',
            'payload.originalOrderId' => 'string',
            'payload.store_id' => 'integer',
            'payload.storeID' => 'integer',
            'payload.customer' => 'array',
            'payload.customer.name' => 'string|nullable',
            'payload.customer.phone' => 'string',
            'payload.delivery.expectedFrom' => 'string',
            'payload.delivery.expectedTo' => 'string',
            'payload.positions' => 'array',

//TODO:: тут приходит массив, непонятно как его валидовать
//            'payload.positions.id' => 'required',
//            'payload.positions.originalQuantity' => 'required',
//            'payload.positions.quantity' => 'required',
//            'payload.positions.price' => 'required',
//            'payload.positions.discountPrice' => 'required',
//            'payload.positions.replacedByID' => 'required',
//            'payload.positions.weight' => 'required',
//            'payload.positions.totalPrice' => 'required',
//            'payload.positions.totalDiscountPrice' => 'required',
            'payload.total' => 'array',
            'payload.total.totalPrice' => 'string',
            'payload.total.discountTotalPrice' => 'string',
            'payload.comment' => 'string|nullable',
            'payload.replacementPolicy' => 'string',
            'payload.paymentMethods' => 'array',
            'payload.shipmentMethod' => 'string',
        ];
    }
}
