<?php

namespace App\Orchid\Screens\Shop\Category;

use App\Orchid\Layouts\Shop\Category\CategoryFiltersLayout;
use App\Orchid\Layouts\Shop\Category\CategoryListLayout;
use Domain\Product\Models\Category;
use Illuminate\Http\Request;
use Orchid\Screen\Screen;

class CategoryListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public ?string $name = 'Список категорий товаров';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'categories' => Category::filtersApplySelection(CategoryFiltersLayout::class)
                ->filters()
                ->with('images')
                ->defaultSort('id', 'desc')
                ->paginate()
        ];
    }

    /**
     * Views.
     *
     * @return string[]
     */
    public function layout(): array
    {
        return [
            CategoryFiltersLayout::class,
            CategoryListLayout::class
        ];
    }

    public function activate(Request $request): void
    {
        $categoryId = $request->get('id');
        $activate = (bool) $request->get('activate', false);

        Category::query()->where('id', $categoryId)->first()?->activate($activate);
    }
}
