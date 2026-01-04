<?php

namespace Domain\Product\Services\Review;

use Domain\Product\Models\Product;
use Domain\Product\Models\Review;

class ProductRatingService
{
    public function updateProductRating(Product $product, string $slug): void
    {
        $product->rating = $this->getAvgRatingReviews($slug);
        $product->save();
    }

    private function getAvgRatingReviews(string $slug): float
    {
        $reviews = Review::
        select([
            'reviews.rating',
        ])
            ->leftJoin('products', 'products.id', '=', 'reviews.product_id')
            ->where('products.slug', $slug)
            ->where('reviews.active', true)
            ->get();

        return round($reviews->avg('rating'), 2);
    }
}
