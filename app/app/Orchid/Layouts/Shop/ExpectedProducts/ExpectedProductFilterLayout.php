<?php


namespace App\Orchid\Layouts\Shop\ExpectedProducts;

use App\Orchid\Filters\Product\ProductFilter;
use App\Orchid\Filters\User\UserFilter;
use Orchid\Screen\Layouts\Selection;

class ExpectedProductFilterLayout extends Selection
{
    /**
     * @return array
     */
    public function filters(): array
    {
        return [
            ProductFilter::class,
            UserFilter::class,
        ];
    }
}
