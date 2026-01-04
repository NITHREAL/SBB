<?php

namespace App\Console\Commands\Category;

use Domain\Product\Services\Category\Children\CategoryChildrenService;
use Illuminate\Console\Command;

class UpdateCategoryChildrenList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'category:update-children-list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Обновление поля дочерних категорий у категории';

    /**
     * Execute the console command.
     */
    public function handle(CategoryChildrenService $categoryChildrenService): void
    {
        $categoryChildrenService->updateCategoriesChildrenSystemId();
    }
}
