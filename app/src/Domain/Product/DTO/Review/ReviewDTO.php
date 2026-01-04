<?php

namespace Domain\Product\DTO\Review;

use Domain\User\Models\User;
use Illuminate\Support\Arr;

class ReviewDTO
{
    public function __construct(
        private readonly string $productSlug,
        private readonly int $rating,
        private readonly ?string  $text,
        private readonly int $userId,
        private readonly ?string $userFirstName,
        private readonly ?string $userLastName,
    ) {
    }

    public static function make(array $data, User $user): self
    {
        return new self(
            Arr::get($data, 'slug'),
            Arr::get($data, 'rating'),
            Arr::get($data, 'text') ?? '',
            $user->id,
            $user->first_name,
            $user->last_name,
        );
    }

    public function getProductSlug(): string
    {
        return $this->productSlug;
    }

    public function getRating(): int
    {
        return $this->rating;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getUserFirstName(): ?string
    {
        return $this->userFirstName;
    }

    public function getUserLastName(): ?string
    {
        return $this->userLastName;
    }
}
