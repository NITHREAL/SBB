<?php

namespace App\Orchid\Screens\Shop\RecommendedProduct;

use App\Orchid\Layouts\Shop\RecommendedProduct\RecommendedProductListLayout;
use Domain\Product\Models\RecommendedProduct;
use Domain\Product\Requests\Admin\RecommendedProduct\RecommendedProductsRequest;
use Domain\Product\Services\RecommendedProduct\RecommendedProductService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class RecommendedProductListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public ?string $name = 'Рекомендуемые товары';

    public ?string $description = 'Раздел управления рекомендованными товарами позволяет вам настроить,
    какие товары будут отображаться пользователям, если их корзина пуста';

    public function __construct(
        private readonly RecommendedProductService $recommendedProductService,
    ) {
    }

    public function query(): array
    {
        $recommendedProducts = RecommendedProduct::query()
            ->orderBy('sort')
            ->with(['product'])
            ->get();

        return [
            'recommendedProducts'  => $recommendedProducts,
        ];
    }

    public function commandBar() : array
    {
        return [
            Button::make('Сохранить')->method('save'),
            Button::make('Очистить')->method('clear'),
        ];
    }

    public function layout(): array
    {
        return [
            RecommendedProductListLayout::class,
        ];
    }

    public function save(RecommendedProductsRequest $request): RedirectResponse
    {
        $recommendedProducts = Arr::get($request->validated(), 'recommendedProducts', []);

        $this->recommendedProductService->updateRecommendedProducts($recommendedProducts);

        Toast::success('Список рекомендуемых товаров успешно сохранены');

        return redirect()->route('platform.recommended-products');
    }

    public function clear(): RedirectResponse
    {
        $this->recommendedProductService->clearRecommendedProducts();

        Toast::success('Список рекомендуемых товаров успешно очищен');

        return redirect()->route('platform.recommended-products');
    }
}
