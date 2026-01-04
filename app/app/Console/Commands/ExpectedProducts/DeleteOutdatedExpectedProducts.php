<?php

namespace App\Console\Commands\ExpectedProducts;

use App\Jobs\SyncCSETracker;
use Domain\Product\Jobs\DeleteOutdatedExpectedProductsJob;
use Domain\Product\Services\ExpectedProduct\ExpectedProductChangeServices;
use Illuminate\Console\Command;

class DeleteOutdatedExpectedProducts extends Command
{
    private ExpectedProductChangeServices $expectedProductsService;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-expected-products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(ExpectedProductChangeServices $expectedProductsService)
    {
        $expectedProductsService->deleteOutdatedExpectedProducts();
    }
}
