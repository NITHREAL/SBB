<?php

namespace Domain\Farmer\Services;

use Domain\Farmer\Models\Farmer;
use Domain\Product\Models\Product;
use Illuminate\Support\Collection;

class FarmerRatingService
{
    private array $reviewInfo = [
        '1' => 0,
        '2' => 0,
        '3' => 0,
        '4' => 0,
        '5' => 0,
    ];

    public function __construct(
        private readonly FarmerReviewService $farmerReviewService,
    ) {
    }

    public function updateFarmerRatingInfo(string $farmer1cId): void
    {
        $farmer = Farmer::query()
            ->where('system_id', $farmer1cId)
            ->first();

        $reviews = $this->farmerReviewService->getFarmersProductsReviews([$farmer->id]);

        $farmer->rating = $this->getAvgRatingProducts($reviews);
        $farmer->review_info = $this->getFarmerRatingInfo($reviews);

        $farmer->save();
    }

    private function getAvgRatingProducts(Collection $reviews): float
    {
        return round($reviews->avg('rating'), 2);
    }

    private function getFarmerRatingInfo(Collection $reviews): array
    {
        foreach ($this->reviewInfo as $key => $value) {
            $this->reviewInfo[$key] = $reviews->where('rating', $key)->count();
        }

        return $this->reviewInfo;
    }
}
