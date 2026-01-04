<?php

namespace Domain\Order\Services;

use Domain\Basket\Services\BasketService;
use Domain\Order\Builders\OrderBuilder;
use Domain\Order\DTO\OrderDeliveryDTO;
use Domain\Order\DTO\OrderDTO;
use Domain\Order\Exceptions\OrderException;
use Domain\Order\Helpers\Payment\OnlinePaymentHelper;
use Domain\Order\Helpers\Payment\PaymentHelper;
use Domain\Order\Jobs\Payment\OrderPreAuthPaymentJob;
use Domain\Order\Models\Order;
use Domain\Order\Models\Payment\OnlinePayment;
use Domain\Order\Services\Delivery\Exceptions\DeliveryTypeException;
use Domain\Order\Services\Payment\Exceptions\RegisterPaymentDoException;
use Domain\Order\Services\Payment\Exceptions\RegisterPreAuthException;
use Domain\Order\Services\Payment\PaymentService;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

readonly class OrderService
{
    public function __construct(
        private BasketService  $basketService,
        private PaymentService $paymentService,
    ) {
    }

    /**
     * @throws OrderException
     * @throws DeliveryTypeException
     */
    public function createOrders(OrderDTO $orderDTO): Collection
    {
        $orders = collect();

        $deliveryParams = $orderDTO->getDelivery();
        $basket = $this->basketService->getBasket();

        /** @var OrderDeliveryDTO $delivery */
        foreach ($deliveryParams as $delivery) {
            $order = $this->createOrder($orderDTO, $basket, $delivery);

            $orders->push($order);
        }

        if ($orders->count()) {
            $this->basketService->clearAll();
        }

        return $orders;
    }

    /**
     * @throws OrderException
     * @throws Delivery\Exceptions\DeliveryTypeException
     * @throws RegisterPreAuthException
     * @throws RegisterPaymentDoException
     */
    public function createOrder(
        OrderDTO $orderDTO,
        array $basket,
        OrderDeliveryDTO $delivery = null,
    ): Order {
        // В случае, если заказ создается нестандартным способом (создание инициировано не со стороны клиента)
        // и в единственном экземпляре, то в DTO будет только один массив с данными по доставке
        $delivery = $delivery ?? Arr::first($orderDTO->getDelivery());

        $this->checkPaymentTypeAvailability($orderDTO->getPaymentType(), $delivery->getDeliveryDate());

        $order = $this->getOrderBuilder($orderDTO, $basket, $delivery)->create();

        if (PaymentHelper::isPaymentOnline($order->payment_type)) {
            $this->handleOnlinePayment($order);
        } elseif (PaymentHelper::isPaymentSBP($order->payment_type)) {
            $this->handleSBPPayment($order);
        } elseif (PaymentHelper::isPaymentTypeSberpay($order->payment_type)) {
            $this->handleSberbankOnlinePayment($order);
        }

        return $order;
    }

    /**
     * @throws RegisterPaymentDoException
     * @throws RegisterPreAuthException
     */
    private function handleOnlinePayment(Order $order): void
    {
        // Если у заказа уже есть связка, то ссылки на платеж не будет.
        // Если связки нет, то будет ссылка на первичный платеж
        if (empty($order->binding_id)) {
            // Если для группы заказов ещё не было сформировано первичного платежа, то необходимо его сформировать
            if (!$this->batchPaymentsExists($order->batch)) {
                $paymentResponse = $this->paymentService->registerPayment($order);
                $formUrl = $paymentResponse->getFormUrl();

                if ($formUrl != null) {
                    $order->pay_url = $formUrl;
                    $order->save();
                }
            }
        } else {
            OrderPreAuthPaymentJob::dispatch($order)->delay(15);
        }
    }

    /**
     * @throws RegisterPreAuthException
     */
    private function handleSBPPayment(Order $order): void
    {
        $sbpPaymentResponse = $this->paymentService->registerSBPPayment($order);

        $formUrl = $sbpPaymentResponse->getFormUrl();

        if ($formUrl != null) {
            $order->pay_url = $formUrl;
            $order->save();
        }
    }

    private function handleSberbankOnlinePayment(Order $order): void
    {
        $sberbankOnlinePaymentResponse = $this->paymentService->registerSberbankOnlinePayment($order);

        $payUrl = Arr::get($sberbankOnlinePaymentResponse->getExternalParams(), 'sbolDeepLink');

        if ($payUrl != null) {
            $order->pay_url = $payUrl;
            $order->save();
        }
    }

    /**
     * @throws OrderException|Delivery\Exceptions\DeliveryTypeException
     */
    private function getOrderBuilder(
        OrderDTO $orderDTO,
        array $basket,
        OrderDeliveryDTO $orderDeliveryDTO,
    ): OrderBuilder {
        $deliverydate = $orderDeliveryDTO->getDeliveryDate();
        $coupon = Arr::get($basket, 'coupon');
        $promocode = Arr::get($basket, 'promocode');

        $basketData = $this->getOrderBasket(Arr::get($basket, 'baskets', []), $deliverydate);
        $orderSettings = Arr::get($basket, 'settings', []) ?? [];

        if (empty($basketData)) {
            throw new OrderException("Не найдена корзина для даты доставки - [{$deliverydate}]");
        }

        return (new OrderBuilder())
            ->setStoreAndPolygon($orderDeliveryDTO)
            ->setDeliveryType($orderDeliveryDTO->getDeliveryType())
            ->setPaymentType($orderDTO->getPaymentType())
            ->setDeliverySubType($orderDeliveryDTO->getDeliverySubType())
            ->setDeliveryDateAndTime(
                $deliverydate,
                $orderDeliveryDTO->getDeliveryTime(),
                $orderDeliveryDTO->getDeliveryType(),
            )
            ->setComment($orderDTO->getComment())
            ->setAddress($orderDeliveryDTO->getAddress())
            ->setCityId($orderDeliveryDTO->getCityId())
            ->setUser()
            ->setBasketData($basketData)
            ->setOrderSettings($orderSettings)
            ->setCoupon($coupon)
            ->setPromocode($promocode)
            ->setBinding($orderDTO->getBindingId())
            ->setSource($orderDTO->getSource())
            ->setUtm($orderDTO->getUtm())
            ->setCourierService($orderDeliveryDTO->getDeliveryService())
            ->setElectronicChecks($orderDTO->getElectronicChecks())
            ->setPayerIp($orderDTO->getPayerIp());
    }

    /**
     * @throws OrderException
     */
    private function checkPaymentTypeAvailability(string $paymentType, string $deliveryDate): void
    {
        if (
            PaymentHelper::isPaymentOnline($paymentType)
            && !$this->isPaymentByOnlineAvailable($deliveryDate)
        ) {
            throw new OrderException(__('messages.payment_online_unavailable'));
        }
    }

    private function isPaymentByOnlineAvailable(string $deliveryDate): bool
    {
        return OnlinePaymentHelper::paymentByOnlineAvailable($deliveryDate);
    }

    private function getOrderBasket(array $baskets, string $deliveryDate): ?array
    {
        return Arr::first(
            Arr::where($baskets, function ($basket) use ($deliveryDate) {
                $basketDate = Arr::get($basket, 'date');
                $products = Arr::get($basket, 'products');

                // Дата доставки/самовывоза корзины такая же и товары в корзине есть
                return $basketDate == $deliveryDate && $products?->count();
            })
        );
    }

    private function batchPaymentsExists(string $batchNumber): bool
    {
        return OnlinePayment::query()->whereBatch($batchNumber)->exists();
    }
}
