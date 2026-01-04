<?php

namespace Domain\Order\DTO\Review;

use Domain\User\Models\User;
use Illuminate\Support\Arr;

class OrderReviewDTO
{
    public function __construct(
        private int $rating,
        private ?string $text,
        private int $orderId,
        private User $user,
    ) {
    }

    public static function make(array $data, int $orderId, User $user): self
    {
        return new self(
            Arr::get($data, 'rating'),
            Arr::get($data, 'text'),
            $orderId,
            $user,
        );
    }

    public function getRating(): int
    {
        return $this->rating;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function getOrderId(): int
    {
        return $this->orderId;
    }

    public function getUserId(): int
    {
        return $this->user->id;
    }
}
