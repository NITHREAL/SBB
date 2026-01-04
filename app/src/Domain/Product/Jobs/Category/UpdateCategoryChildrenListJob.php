<?php

namespace Domain\Product\Jobs\Category;

use Domain\Product\Services\Category\Children\CategoryChildrenService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateCategoryChildrenListJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(CategoryChildrenService $categoryChildrenService): void
    {
        $categoryChildrenService->updateCategoriesChildrenSystemId();
    }
}
