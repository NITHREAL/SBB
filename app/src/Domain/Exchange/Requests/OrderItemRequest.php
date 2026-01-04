<?php

declare(strict_types=1);

namespace Domain\Exchange\Requests;

use Domain\Order\Enums\Delivery\DeliveryTypeEnum;
use Domain\Order\Enums\OrderStatusEnum;
use Domain\Order\Services\Delivery\DateServices\ReceiveInterval;
use Illuminate\Validation\Rule;

class OrderItemRequest extends ItemRequest
{
    public function rules(): array
    {
        $rules = $this->baseRules();

        if ($this->isDelivery()) {
            $rules = array_merge($rules, $this->deliveryRules());
        }

        return $rules;
    }

    private function baseRules(): array
    {
        return [
            'system_id' => ['required'],
            'status' => [Rule::in(OrderStatusEnum::toValues())],
            'payment_type' => ['exists:payments,code'],
            'delivery_type' => [Rule::in(DeliveryTypeEnum::toValues())],

            'delivery_price' => ['string', 'nullable'],
            'delivery_service' => ['string', 'nullable'],
            'delivery_cost' => ['string', 'nullable'],
            'receive_date' => ['string', 'nullable'],
            'receive_interval' => ['string', 'nullable'],

            'comment' => ['string', 'nullable'],
            'promo' => ['string', 'exists:promos,code', 'nullable'],
            'contacts.phone' => ['nullable'],
            'contacts.name' => ['string', 'nullable'],
            'contacts.email' => ['nullable'],
            'products.*.system_id' => ['required', 'exists:products,system_id'],
            'products.*.unit_system_id' => ['required', 'exists:units,system_id'],
            'products.*.count' => ['required', 'numeric', 'min:0'],
            'products.*.price' => ['required', 'numeric', 'min:0'],
            'products.*.price_buy' => ['required', 'numeric', 'min:0'],
            'products.*.total' => ['required', 'numeric', 'min:0'],
            'products.*.total_without_discount' => ['required', 'numeric', 'min:0'],
        ];
    }

    private function deliveryRules(): array
    {
        return [
            'delivery_cost' => ['numeric', 'min:0'], // ToDo: calc delivery cost
            'receive_date' => ['date_format:d-m-Y', 'nullable'],
            'receive_interval' => ['regex:' . ReceiveInterval::PATTERN, 'nullable'],
            'contacts.address' => ['string', 'nullable'],
            'contacts.apartment' => ['integer', 'min:1', 'nullable'],
            'contacts.floor' => ['integer', 'min:1', 'nullable'],
            'contacts.entrance' => ['integer', 'min:1', 'nullable'],
            'contacts.has_elevator' => ['boolean', 'nullable'],
            'contacts.intercom' => ['string', 'nullable'],
        ];
    }

    private function isDelivery(): bool
    {
        return $this->delivery_type === DeliveryTypeEnum::delivery()->value;
    }
}
