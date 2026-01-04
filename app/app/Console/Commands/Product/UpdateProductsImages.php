<?php

namespace App\Console\Commands\Product;

use Domain\Product\Services\Image\ImageSyncService;
use Illuminate\Console\Command;

class UpdateProductsImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product:update-images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get images from site and attach them to products';

    /**
     * Execute the console command.
     * @throws \Exception
     */
    public function handle(ImageSyncService $imageSyncService): void
    {
        $imageSyncService->syncImages();
    }
}
