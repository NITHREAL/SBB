<?php

namespace Domain\Product\Listeners;

use Domain\Product\Events\ReviewCreated;
use Domain\Product\Services\Review\ProductRatingService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class ProductRatingUpdateListener implements ShouldQueue
{
    public function __construct(
        protected readonly ProductRatingService $productRatingService
    ) {
    }

    public function handle(ReviewCreated $event): void
    {
        $this->productRatingService->updateProductRating($event->product, $event->slug);
    }

    public function failed(ReviewCreated $event, \Throwable $exception): void
    {
        Log::channel('message')->error(
            'review_'.$exception->getMessage(),
        );
    }


}
