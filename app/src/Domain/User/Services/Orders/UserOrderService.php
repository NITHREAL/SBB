<?php

namespace Domain\User\Services\Orders;

use Domain\Order\Enums\OrderStateEnum;
use Domain\Order\Enums\OrderStatusEnum;
use Domain\Order\Helpers\Delivery\OrderDeliveryHelper;
use Domain\Order\Helpers\OrderHelper;
use Domain\Order\Models\Order;
use Domain\Order\Services\Review\OrderReviewService;
use Domain\User\DTO\Order\UserClosestOrderDTO;
use Domain\User\DTO\Order\UserOrdersDTO;
use Domain\User\DTO\Order\UserOrdersHistoryDTO;
use Domain\User\Exceptions\ClosestOrderException;
use Domain\User\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class UserOrderService
{
    public function __construct(
        private readonly UserOrderProductsService $userOrderProductsService,
        private readonly OrderReviewService $orderReviewService,
    ) {
    }

    public function getOrders(UserOrdersDTO $userOrdersDTO): LengthAwarePaginator
    {
        $state = $userOrdersDTO->getState();

        $orders = Order::query()
            ->whereUser($userOrdersDTO->getUser()->id)
            ->when($userOrdersDTO->getType(), function ($query) use ($userOrdersDTO) {
                return $query->whereType($userOrdersDTO->getType());
            })
            ->when($userOrdersDTO->getDeliveryType(), function ($query) use ($userOrdersDTO) {
                return $query->where('orders.delivery_type', $userOrdersDTO->getDeliveryType());
            })
            ->when($state, function ($query) use ($state) {
                match($state) {
                    OrderStateEnum::pending()->value    => $query->whereStatePending(),
                    OrderStateEnum::finished()->value   => $query->whereStateFinished(),
                };

                return $query;
            })
            ->orderByDesc('created_at')
            ->paginate($userOrdersDTO->getLimit());

        return $this->getPreparedOrderPaginatedCollection($orders);
    }

    public function getAllOrders(UserOrdersHistoryDTO $userOrdersHistoryDTO): LengthAwarePaginator
    {
        $orders = Order::query()
            ->whereUser($userOrdersHistoryDTO->getUser()->id)
            ->orderByDesc('created_at')
            ->paginate($userOrdersHistoryDTO->getLimit());

        return $this->getPreparedOrderPaginatedCollection($orders);
    }

    public function getOrder(int $id, User $user): object
    {
        $order = Order::query()
            ->whereId($id)
            ->whereUser($user->id)
            ->firstOrFail();

        return $this->getPreparedOrder($order);
    }

    /**
     * @throws ClosestOrderException
     */
    public function getUserClosestOrder(UserClosestOrderDTO $userClosestOrderDTO): object
    {
        $orders = Order::query()
            ->whereUser($userClosestOrderDTO->getUser()->id)
            ->whereNotCompleted()
            ->latest('created_at')
            ->first();

        if (!$orders) {
            throw new ClosestOrderException();
        }

        return $this->getPreparedOrder($orders);
    }

    private function getPreparedOrderPaginatedCollection(LengthAwarePaginator $orders): LengthAwarePaginator
    {
        $orderIds = $orders->pluck('id')->toArray();

        $products = $this->userOrderProductsService->getProducts($orderIds);
        $reviews = $this->orderReviewService->getReviews($orderIds);

        $preparedOrderCollection = $orders
            ->getCollection()
            ->map(function ($order) use ($products, $reviews) {
                return $this->prepareOrder($order, $products, $reviews);
            });

        return $orders->setCollection($preparedOrderCollection);
    }

    public function getPreparedOrder(object $order): object
    {
        $products = $this->userOrderProductsService->getProducts([$order->id]);
        $reviews = $this->orderReviewService->getReviews([$order->id]);

        return $this->prepareOrder($order, $products, $reviews);
    }

    private function prepareOrder(object $order, Collection $products, Collection $reviews): object
    {
        $orderReview = $reviews->where('order_id', $order->id)->first();

        $order->products = $products->where('orderId', $order->id);
        $order->productsTotal = round($order->products->sum(fn($item) => $item->total));
        $order->deliveryCost = round($order->delivery_cost, 2);
        $order->total = round(OrderHelper::getTotal($order, true, true), 2);
        $order->reviewAvailable = $this->isReviewAvailable($order, $orderReview);
        $order->rate = $orderReview?->rate;
        $order->address = OrderDeliveryHelper::isDelivery($order->delivery_type)
            ? $order->contacts?->address
            : sprintf('г %s, %s', $order->store->city->title, $order->store->title);

        return $order;
    }

    private function isReviewAvailable(object $order, ?object $review): bool
    {
        return empty($review) && $order->status === OrderStatusEnum::completed()->value;
    }
}
