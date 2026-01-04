<?php

namespace Domain\Product\Services\ExpectedProduct;

use App\Orchid\Layouts\Shop\ExpectedProducts\ExpectedProductFilterLayout;
use Domain\Product\Models\ExpectedProduct;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ExpectedProductService
{
    public function getExpectedProductData(): Collection
    {
        return $this->getExpectedProductDataQuery()->get();
    }

    public function getExpectedProductPaginatedData(): LengthAwarePaginator
    {
        return $this->getExpectedProductDataQuery()->paginate();
    }

    private function getExpectedProductDataQuery(): Builder
    {
        return ExpectedProduct::filtersApplySelection(ExpectedProductFilterLayout::class)
            ->with(['product', 'user'])
            ->filters()
            ->defaultSort('product_id', 'desc');
    }

}
