<?php

namespace Domain\Product\Jobs\Leftovers;

use Domain\Product\Services\Leftover\ProductLeftoversGenerateService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class ProductLeftoversGenerateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private readonly Collection $products,
        private readonly Collection $stores,
    ) {
    }

    public function handle(ProductLeftoversGenerateService $leftoversGenerateService): void
    {
        $leftoversGenerateService->generateProductsLeftovers($this->products, $this->stores);
    }
}
