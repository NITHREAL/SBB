<?php

namespace Domain\Exchange\Resources;

use Domain\Order\Enums\Payment\PaymentStatusEnum;
use Domain\Order\Helpers\Delivery\OrderDeliveryHelper;
use Domain\Order\Models\Order;
use Domain\Order\Models\Payment\OnlinePayment;
use Domain\Order\Resources\Order\OrderSettingsResource;
use Domain\UtmLabel\Enums\UtmLabelEnum;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class OrderResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var Order $order */
        $order = $this->resource;

        $isPickup = OrderDeliveryHelper::isPickup($this->delivery_type);

        $isFirstOrder = Order::query()->where('user_id', $order->user_id)->count() === 1;

        return [
            'id' => (int)$this->id,
            'system_id' => $this->resource['system_id'],
            'store_system_id' => (string)$this->store_system_id,
            'status' => (string)$this->status,
            'payment_type' => (string)$this->payment_type,
            'delivery_type' => (string)$this->delivery_type,
            'delivery_cost' => !$isPickup ? $this->delivery_cost : null,
            'receive_date' => $this->receive_date,
            'receive_interval' => $this->receive_interval,

            // ToDo: promo codes,
            'promo' => $this->whenLoaded('promo', new OrderPromoResource($this->promo)),

            // ToDo: online payment
            'paid_amount' => $order->paymentByOnline ? $this->getPaidAmount() : null,
            'need_receipt' => (bool)$this->need_receipt,
            'comment' => (string)$this->comment,
            'is_first_order' => $isFirstOrder,
            'rrn' => $this->getRRN($order),
            'source' => $this->utm?->where('type', UtmLabelEnum::utmSource()->value)->first()?->value,
            'created_at' => (int)$this->created_at->getTimestamp(),
            'user' => new OrderUserResource($this->user),
            'contacts' => (new OrderContactResource($this->contacts)),
            'products' => OrderProductResource::collection($this->products),
            'settings' => OrderSettingsResource::make($this->settings),
        ];
    }

    private function getPaidAmount()
    {
        $order = $this->resource;

        /** @var Collection<OnlinePayment> $payments */
        $payments = $order->payments
            ->where('payed', true)
            ->whereIn('status', [
                PaymentStatusEnum::hold()->value,
                PaymentStatusEnum::deposit()->value
            ]);

        $amount = $payments->sum(fn (OnlinePayment $payment) => $payment->pivot->amount ?? 0);

        return round($amount, 2);
    }

    private function getRRN(Order $order): array
    {
        $payments = $order->payments;
        $rrnArr = [];

        foreach ($payments as $payment) {
            foreach ($payment->logs as $log) {
                $response = $log->response;
                $rrn = Arr::get($response, 'authRefNum');

                if ($rrn) {
                    $rrnArr[] = $rrn;
                }
            }
        }

        return $rrnArr;
    }
}
