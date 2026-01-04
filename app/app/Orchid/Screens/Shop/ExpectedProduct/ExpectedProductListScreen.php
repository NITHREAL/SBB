<?php

namespace App\Orchid\Screens\Shop\ExpectedProduct;

use App\Orchid\Core\Actions;
use App\Orchid\Helpers\Concerns\CreateExportAction;
use App\Orchid\Layouts\Shop\ExpectedProducts\ExpectedProductFilterLayout;
use App\Orchid\Layouts\Shop\ExpectedProducts\ExpectedProductListLayout;
use Domain\Product\Services\ExpectedProduct\ExpectedProductService;
use Orchid\Screen\Screen;

class ExpectedProductListScreen extends Screen
{
    use CreateExportAction;
    /**
     * Display header name.
     *
     * @var string
     */
    public string $name;

    public function __construct(
        private readonly ExpectedProductService $expectedProductService,
    ) {
        $this->name = __('admin.expected_product.title');
    }

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'expected_products' => $this->expectedProductService->getExpectedProductPaginatedData()
        ];
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): array
    {
        $filter = request()->all();

        return Actions::make([
            (new Actions\Export('expected_products', $filter))->setTitle($this->name),
        ]);
    }

    /**
     * Views.
     *
     * @return string[]
     */
    public function layout(): array
    {
        return [
            ExpectedProductFilterLayout::class,
            ExpectedProductListLayout::class
        ];
    }
}
