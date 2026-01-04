<?php

namespace Domain\Farmer\Services;

use Domain\Farmer\Models\Farmer;
use Domain\Product\Models\Review;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FarmerReviewService
{
    private const DEFAULT_FARMERS_REVIEW_LIMIT = 10;

    public function getFarmersReview(
        string $slug,
        ?int $limit,
    ): LengthAwarePaginator {
        $limit = $limit ?? self::DEFAULT_FARMERS_REVIEW_LIMIT;

        $farmer = Farmer::query()->whereSlug($slug)->first();

        if (is_null($farmer)) {
            throw new NotFoundHttpException("фермера с slug: $slug не существует");
        }

        return Review::query()
            ->farmersQuery([$farmer->id])
            ->paginate($limit);
    }

    public function getFarmersProductsReviews(array $farmerIds): Collection
    {
        return Review::query()
            ->farmersQuery($farmerIds)
            ->get();
    }
}
