<?php


namespace App\Orchid\Layouts\Shop\Product;

use App\Orchid\Filters\ActiveFilter;
use App\Orchid\Filters\IdFilter;
use App\Orchid\Filters\Product\ProductArticleFilter;
use App\Orchid\Filters\SortFilter;
use App\Orchid\Filters\TitleFilter;
use Orchid\Screen\Layouts\Selection;

class ProductFilterLayout extends Selection
{
    /**
     * @return array
     */
    public function filters(): array
    {
        return [
            IdFilter::class,
            ActiveFilter::class,
            ProductArticleFilter::class,
            TitleFilter::class,
            SortFilter::class
        ];
    }
}
