<?php

namespace Domain\Order\Services\Review;

use Domain\Order\DTO\Review\OrderReviewDTO;
use Domain\Order\Exceptions\OrderException;
use Domain\Order\Models\Order;
use Domain\Order\Models\OrderReview;
use Illuminate\Support\Collection;

class OrderReviewService
{
    /**
     * @throws OrderException
     */
    public function create(OrderReviewDTO $reviewDTO): object
    {
        $order = Order::query()
            ->whereCompleted()
            ->whereUser($reviewDTO->getUserId())
            ->whereId($reviewDTO->getOrderId())
            ->first();

        if (empty($order)) {
            throw new OrderException('Заказ с таким идентификатором не найден или недоступен');
        }

        $review = new OrderReview();

        $review->fill([
            'rate'  => $reviewDTO->getRating(),
            'text'  => $reviewDTO->getText(),
        ]);

        $review->order()->associate($order);

        $review->save();

        return $review;
    }

    public function getReviews(array $orderIds): Collection
    {
        return OrderReview::query()
            ->whereIn('order_id', $orderIds)
            ->get();
    }
}
