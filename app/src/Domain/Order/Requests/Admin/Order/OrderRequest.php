<?php

namespace Domain\Order\Requests\Admin\Order;

use Domain\Order\Enums\Delivery\DeliveryTypeEnum;
use Domain\Order\Enums\Delivery\PickupTypeEnum;
use Domain\Order\Enums\Delivery\PolygonDeliveryTypeEnum;
use Domain\Order\Enums\OrderStatusEnum;
use Domain\Order\Helpers\Delivery\OrderDeliveryHelper;
use Domain\Order\Helpers\OrderStatusHelper;
use Domain\Order\Models\Order;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

class OrderRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        /** @var Order $orderModel */
        $this->orderModel = $this->route('order');

        $this->merge([
            'order' => array_merge($this->orderModel->toArray(), $this->order)
        ]);

        $deliveryType = Arr::get($this->order, 'delivery_type');
        $isPickup = OrderDeliveryHelper::isPickup($deliveryType);

        if ($isPickup) {
            $this->merge([
                'order' => array_merge($this->order, [
                    'bill' => null
                ])
            ]);
        }

        $receiveInterval = $this->order['receive_interval'];

        $this->merge([
            'order' => array_merge($this->order, [
                'receive_interval' => sprintf('%s_%s', Arr::get($receiveInterval, 'from'), Arr::get($receiveInterval, 'to'))
            ])
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $order = $this->order;

        $deliveryType = Arr::get($order, 'delivery_type');
        $deliverySubTypes = OrderDeliveryHelper::isPickup($deliveryType)
            ? PickupTypeEnum::toValues()
            : PolygonDeliveryTypeEnum::toValues();
        $isDelivery = OrderDeliveryHelper::isDelivery($deliveryType);
        $isAnyDelivery = $isDelivery;

        $completed = in_array($order['status'], OrderStatusHelper::getFinishedStatuses());

        $excludeIfCompleted =
            'exclude_if:status,' . OrderStatusEnum::completed()->value .
            'exclude_if:status,' . OrderStatusEnum::canceled()->value .
            'exclude_if:status,' . OrderStatusEnum::canceledByCustomer()->value;

        $rules = [
            'order.status' => [
                'required',
                Rule::in(OrderStatusEnum::toValues())
            ],
            'order.payment_type' => [
                Rule::requiredIf(!$completed),
                $excludeIfCompleted,
                'exists:payments,code'
            ],
            'order.delivery_type' => [
                Rule::requiredIf(!$completed),
                $excludeIfCompleted,
                Rule::in(DeliveryTypeEnum::toValues())
            ],
            'order.delivery_sub_type' => [
                Rule::requiredIf(!$completed),
                $excludeIfCompleted,
                Rule::in($deliverySubTypes)
            ],
            'order.delivery_cost' => [
                $excludeIfCompleted,
                'numeric',
                'min:0',
                'nullable'
            ],
            'order.receive_date' => [
                Rule::requiredIf(!$completed && $isAnyDelivery),
                $excludeIfCompleted,
                'date_format:d-m-Y',
                'nullable'
            ],
            'order.receive_interval' => [
                Rule::requiredIf(!$completed && $isAnyDelivery),
                $excludeIfCompleted,
                //'regex:' . ReceiveInterval::PATTERN,
                'nullable'
            ],
            'order.bill' => [
                //Rule::in(OrderChangeFromBillEnum::toValues()),
                $excludeIfCompleted,
                'nullable'
            ],
            'order.products' => ['array', $excludeIfCompleted],
            'order.products.*.system_id' => 'required|exists:products,system_id',
            'order.products.*.pivot.price' => 'required|numeric|min:0',
            'order.products.*.pivot.price_buy' => 'required|numeric|min:0',
            'order.products.*.pivot.count' => 'required|numeric|min:0',
            'order.products.*.pivot.unit_system_id' => 'required|exists:units,system_id'
        ];

        if (!$this->order) {
            $rules = array_merge([
                'order.store_system_id' => 'required|exists:stores,system_id',
                'order.user_id' => 'required|exists:users,id',
            ], $rules);
        }

        return $rules;
    }
}
