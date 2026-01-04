<?php

namespace App\Orchid\Screens\Shop\ProductFilter;

use App\Models\ProductFilter;
use App\Orchid\Layouts\Shop\ProductFilter\ProductFilterListLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Screen;

class ProductFilterListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Фильтры';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'filters' => ProductFilter::filters()
                ->defaultSort('sort')
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
            ProductFilterListLayout::class
        ];
    }

    public function activate(Request $request)
    {
        $activate = $request->get('activate');
        ProductFilter::findOrFail($request->get('id'))->activate($activate);
    }
}
