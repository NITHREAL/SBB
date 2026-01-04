<?php

declare(strict_types=1);

namespace Domain\Order\Services\Exchange;

use Domain\Order\DTO\Exchange\OrderCreateExchangeDTO;
use Domain\Order\DTO\Exchange\OrderUpdateExchangeDTO;
use Domain\Order\Enums\Exchange\OrderExchangeTypeEnum;
use Domain\Order\Enums\OrderStatusEnum;
use Domain\Order\Models\Delivery\PolygonDeliveryPrice;
use Domain\Order\Models\Order;
use Domain\Order\Services\Delivery\Polygon\PolygonService;
use Domain\Product\Helpers\ProductWeightHelper;
use Domain\Product\Models\Product;
use Domain\Promocode\Models\Promocode;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

readonly class OrderExchangeUpdateService
{
    public function __construct(
        private PolygonService $polygonService,
        private OrderExchangeLogService $exchangeLogService,
    ) {
    }

    /**
     * @throws Exception
     */
    public function createOrder(OrderCreateExchangeDTO $orderDTO): Order
    {
        $order = new Order();
        $dataForOrder = Arr::except($orderDTO->toArray(), ['products', 'contacts']);
        $dataForOrder['need_exchange'] = false;

        return $this->handleOrderTransaction($order, $dataForOrder, $orderDTO->toArray());
    }

    /**
     * @throws Exception
     */
    public function updateOrder(OrderUpdateExchangeDTO $orderDTO): ?Order
    {
        $order1cId = $orderDTO->getSystemId();

        /** @var Order $order */
        $order = Order::query()->whereOneCId($order1cId)->first();

        if ($order) {
            $dataForOrder = Arr::except($orderDTO->toArray(), ['products', 'contacts']);
            $dataForOrder['need_exchange'] = false;

            if (
                Arr::get($dataForOrder, 'status') === OrderStatusEnum::canceled()->value
                && $order->status === OrderStatusEnum::canceledByCustomer()->value
            ) {
                unset($dataForOrder['status']);
            }

            $order = $this->handleOrderTransaction($order, $dataForOrder, $orderDTO->toArray());
        }

        return $order;
    }

    /**
     * @throws Exception
     */
    private function handleOrderTransaction(Order $order, array $dataForOrder, array $orderDTOArray): Order
    {
        DB::beginTransaction();

        try {
            $order = $this->updateOrderRelations($order, $orderDTOArray);

            if ($order->isCompleted) {
                $order->completed_at = Carbon::now();
            }

            $order->fill($dataForOrder);
            $order->save();

            $this->exchangeLogService->logOrderExchange($order, OrderExchangeTypeEnum::import()->value);

            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            Log::channel('message')->error('Error processing order transaction: ' . $exception->getMessage());
            throw $exception;
        }

        return $order;
    }

    private function updateOrderRelations(Order $order, array $data): Order
    {
        $promocode = Arr::get($data, 'promo');
        $contacts = Arr::get($data, 'contacts');
        $products = Arr::get($data, 'products', []);

        $this->updateOrderPromo($order, $promocode);

        if ($contacts) {
            $order->contacts()->update($contacts);
        }

        $productsData = $this->getPreparedProductsData($products);

        $order->products()->sync($productsData);

        if ($deliveryPricePolygon = $order->deliveryPriceInPolygon) {
            $order->delivery_cost = $this->getDeliveryCost($deliveryPricePolygon, $productsData);
        }

        return $order;
    }

    private function updateOrderPromo(Order $order, ?string $promocode): void
    {
        if ($promocode) {
            $promo = Promocode::query()->whereCode($promocode)->first();

            if ($promo) {
                if ($order->promo_id !== $promo->id && !$promo->percentage) {
                    $order->discount = $promo->discount;
                }

                $order->promocode()->associate($promo);

                $order->save();
            }
        }

        if (empty($promo)) {
            $order->promocode()->disassociate();
            $order->discount = null;

            $order->save();
        }
    }

    private function getPreparedProductsData(array $products): array
    {
        $data = [];

        $productsCollection = Product::query()
            ->whereIn('system_id', array_column($products, 'system_id'))
            ->get();

        foreach ($products as $product) {
            $productItem = $productsCollection
                ->where('system_id', $product['system_id'])
                ->first();

            if ($productItem && ProductWeightHelper::isWeightProduct($productItem)) {
                $product['weight'] = 1;
            }

            $alreadyExists = isset($data[$product['system_id']]);

            if ($alreadyExists) {
                $data[$product['system_id']]['count'] = $data[$product['system_id']]['count'] + $product['count'];
                $data[$product['system_id']]['total'] = $data[$product['system_id']]['total'] + $product['total'];
                $data[$product['system_id']]['total_without_discount'] = $data[$product['system_id']]['total_without_discount']
                    + $product['total_without_discount'];
            } else {
                $data[$product['system_id']] = Arr::except($product, 'system_id');
            }
        }

        return $data;
    }

    private function getDeliveryCost(PolygonDeliveryPrice $polygonDeliveryPrice, array $productsData): float
    {
        $result = 0;

        $totalPrice = $this->recalculateOrderPrice($productsData);
        $newDeliveryPriceInPolygon = $this->polygonService->getDeliveryDataBySum(
            $polygonDeliveryPrice->polygon, $totalPrice
        );

        if ($newDeliveryPriceInPolygon) {
            $result = Arr::get($newDeliveryPriceInPolygon, 'price', 0);
        }

        return round($result, 2);
    }

    private function recalculateOrderPrice(array $products): float
    {
        $result = 0;

        foreach ($products as $product) {
            $result += $product['total'] * 100;
        }

        if ($result > 0) {
            $result = $result / 100;
        }

        return $result;
    }
}
