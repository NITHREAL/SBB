<?php

namespace App\Orchid\Screens\Shop\Product;

use App\Orchid\Layouts\Shop\Product\ProductFilterLayout;
use App\Orchid\Layouts\Shop\Product\ProductListLayout;
use Domain\Product\Models\Product;
use Illuminate\Http\Request;
use Orchid\Screen\Screen;

class ProductListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public ?string $name = 'Список товаров';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'products' => Product::filtersApplySelection(ProductFilterLayout::class)
                ->filters()
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
            ProductFilterLayout::class,
            ProductListLayout::class
        ];
    }

    public function activate(Request $request): void
    {
        $id = $request->get('id');
        $activate = (bool)$request->get('activate', false);


        Product::query()->whereId($id)->first()?->activate($activate);
    }
}
