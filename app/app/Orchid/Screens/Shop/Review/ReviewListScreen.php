<?php

namespace App\Orchid\Screens\Shop\Review;

use App\Orchid\Layouts\Shop\Review\ReviewFiltersLayout;
use App\Orchid\Layouts\Shop\Review\ReviewListLayout;
use Domain\Product\Models\Review;
use Illuminate\Http\Request;
use Orchid\Screen\Screen;

class ReviewListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Список отзывов';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        $sort = request('sort', 'id');
        $direction = str_starts_with($sort, '-') ? 'desc' : 'asc';
        $column = ltrim($sort, '-');

        $query = Review::filtersApplySelection(ReviewFiltersLayout::class)
            ->select('reviews.*')
            ->with('user', 'product')
            ->whereHas('product')
            ->filters();

        if ($column === 'product.title') {
            $query->leftJoin('products', 'reviews.product_id', '=', 'products.id')
                ->orderBy('products.title', $direction);
        } else {
            $query->orderBy($column, $direction);
        }

        return [
            'reviews' => $query->paginate()
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
            ReviewFiltersLayout::class,
            ReviewListLayout::class
        ];
    }

    public function activate(Request $request)
    {
        $activate = $request->get('activate');

        Review::findOrFail($request->get('id'))->activate($activate);
    }

    public function softDelete(Request $request)
    {
        Review::findOrFail($request->get('id'))->delete();
    }
}
