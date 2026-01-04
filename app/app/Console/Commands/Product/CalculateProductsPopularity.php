<?php

namespace App\Console\Commands\Product;

use Domain\Product\Services\CalculateProductsPopularityService;
use Illuminate\Console\Command;

class CalculateProductsPopularity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product:calculate-popularity';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate products popularity';

    /**
     * Execute the console command.
     */
    public function handle(CalculateProductsPopularityService $popularityService)
    {
        $popularityService->calculateProductsPopularity();
    }
}
