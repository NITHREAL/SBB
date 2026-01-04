<?php

namespace App\Orchid\Screens\Shop\Product;

use App\Orchid\Core\Actions;
use App\Orchid\Layouts\Shop\Product\ProductInfoRow;
use App\Orchid\Layouts\Shop\Product\ProductLeftoverLayout;
use App\Orchid\Layouts\Shop\Product\ProductRelatedProductsLayout;
use App\Orchid\Screens\Traits\SyncHasMany;
use Domain\Product\DTO\Product\ProductDTO;
use Domain\Product\Models\Product;
use Domain\Product\Requests\Admin\Product\ProductRequest;
use Domain\Product\Services\ProductUpdateService;
use Illuminate\Http\RedirectResponse;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class ProductEditScreen extends Screen
{
    use SyncHasMany;

    /**
     * Display header name.
     *
     * @var string
     */
    public ?string $name = 'Добавить товар';

    public ?Product $product = null;

    public function __construct(
        private readonly ProductUpdateService $productUpdateService,
    ) {
    }

    /**
     * Query data.
     *
     * @return array
     */
    public function query(Product $product): array
    {
        $sort = request('sort', 'created_at');
        $direction = str_starts_with($sort, '-') ? 'desc' : 'asc';

        $column = ltrim($sort, '-');
        if ($product->exists) {
            $product->load(['leftovers' => function ($query) use ($column, $direction) {
                if (in_array($column, ['price', 'price_discount', 'discount_expires_in', 'count'])) {
                    $query->orderBy($column, $direction);
                } elseif ($column === 'store_system_id') {
                    $query->join('stores', 'stores.system_id', '=', 'product_store.store_system_id')
                        ->orderBy('stores.title', $direction);
                }
            }]);

            $product->load('metaTagValues');
            $this->name = $product->title;
        }

        $this->product = $product;

        return [
            'product' => $product,
        ];
    }

    public function commandBar(): array
    {
        return Actions::make([
            Actions\Save::for($this->product)
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
            Layout::tabs([
                __('admin.product.info')                => ProductInfoRow::class,
                __('admin.product.leftovers')           => ProductLeftoverLayout::class,
                __('admin.product.related_products')    => ProductRelatedProductsLayout::class
            ])
        ];
    }

    public function save(Product $product, ProductRequest $request): RedirectResponse
    {
        $productDTO = ProductDTO::make($request->validated());

        $this->productUpdateService->updateProductData($product, $productDTO);

        Toast::success(__('admin.toasts.updated'));

        return redirect()->route('platform.products.list');
    }
}
