<?php

namespace Domain\Order\Handlers;

use Domain\Order\Enums\OrderStatusEnum;
use Domain\Order\Models\Order;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Infrastructure\Services\Messaging\MessageHandler;

class OrderHandler implements MessageHandler
{
    /**
     *
     * @param array $message
     * @return void
     */
    public function handle(array $message): void
    {
        try {
            Log::channel('message')->info(sprintf('Данные по заказу от рэбита - [%s]', json_encode($message)));

            $data = $this->prepareOrderData($message);

            $order = $this->updateOrder($data);

            if ($order->status === OrderStatusEnum::collected()->value) {
                $this->syncOrderProducts($order, Arr::get($message, 'products', []));
            }
        } catch (Exception $e) {
            Log::channel('message')->error('Ошибка при обработке заказа: ' . $e->getMessage());
        }
    }

    /**
     *
     * @param array $message
     * @return array
     */
    private function prepareOrderData(array $message): array
    {
        return [
            'system_id'     => Arr::get($message, 'systemId'),
            'status'        => Arr::get($message, 'status'),
            'comment'       => Arr::get($message, 'comment'),
            'total_price'   => Arr::get($message, 'totalPrice'),
            'discount'      => Arr::get($message, 'discount'),
        ];
    }

    /**
     *
     * @param Order $order
     * @param array $products
     * @return void
     */
    private function syncOrderProducts(Order $order, array $products): void
    {
        $syncData = [];

        foreach ($products as $product) {
            $productSystemId = Arr::get($product, 'systemId');
            $priceDiscount = Arr::get($product, 'priceDiscount');
            $price = Arr::get($product, 'price');

            $syncData[$productSystemId] = [
                'order_id'                  => $order->id,
                'product_system_id'         => $productSystemId,
                'replacement_system_id'     => Arr::get($product, 'replacementSystemId'),
                'unit_system_id'            => Arr::get($product, 'unitSystemId'),
                'price'                     => $price,
                'price_buy'                 => $price,
                'price_discount'            => $priceDiscount,
                'is_discount'               => !empty($priceDiscount),
                'count'                     => Arr::get($product, 'count', 0),
                'original_quantity'         => Arr::get($product, 'originalQuantity'),
                'collected_quantity'        => Arr::get($product, 'collectedQuantity'),
                'total'                     => Arr::get($product, 'total'),
                'total_without_discount'    => Arr::get($product, 'totalWithoutDiscount'),
                'weight'                    => (int) Arr::get($product, 'weight'),
            ];
        }

        $order->products()->sync($syncData);
    }

    private function updateOrder(array $data): Order
    {
        /** @var Order $order */
        $order = Order::query()->where('system_id', $data['system_id'])->firstOrFail();

        $order->fill($data);

        $order->save();

        return $order;
    }
}
