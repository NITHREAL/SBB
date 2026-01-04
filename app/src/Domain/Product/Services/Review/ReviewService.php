<?php

namespace Domain\Product\Services\Review;


use Domain\Product\DTO\Review\ReviewDTO;
use Domain\Product\Events\ReviewCreated;
use Domain\Product\Models\Product;
use Domain\Product\Models\Review;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ReviewService
{
    private const DEFAULT_LIMIT = 10;

    public function getProductReviews(string $productSlug, ?int $limit): LengthAwarePaginator
    {
        if (is_null(Product::where('slug', $productSlug)->first())) {
            throw new NotFoundHttpException("товара с slug: $productSlug не существует");
        }

        return Review::query()
            ->indexQuery($productSlug)
            ->paginate($limit ?? self::DEFAULT_LIMIT);
    }

    public function addProductReview(ReviewDTO $reviewDTO): Review
    {
        $slug = $reviewDTO->getProductSlug();
        $product = Product::select(['id', 'farmer_system_id'])->where('slug', $slug)->first();

        if (is_null($product)) {
            throw new NotFoundHttpException("товара с slug: $slug не существует");
        }

        $userId = $reviewDTO->getUserId();

        $review = Review::firstOrCreate(
            [
                'product_id' => $product->id,
                'user_id' => $userId,
            ],
            [
                'rating' => $reviewDTO->getRating(),
                'text' => $reviewDTO->getText(),
            ],
        );

        if (!$review->wasRecentlyCreated) {
            throw new ConflictHttpException(
                "отзыв пользователя с id: $userId на товар с id: $product->id уже существует"
            );
        }

        $review->setAttribute('user_id',  $userId);
        $review->setAttribute('first_name',  $reviewDTO->getUserFirstName());
        $review->setAttribute('last_name',  $reviewDTO->getUserLastName());

        ReviewCreated::dispatch($product, $slug);

        return $review;
    }
}
