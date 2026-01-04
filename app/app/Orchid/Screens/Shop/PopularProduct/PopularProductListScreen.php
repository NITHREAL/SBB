<?php

namespace App\Orchid\Screens\Shop\PopularProduct;

use App\Orchid\Layouts\Shop\PopularProduct\PopularProductListLayout;
use Domain\Product\Models\PopularProduct;
use Domain\Product\Requests\Admin\PopularProduct\PopularProductsRequest;
use Domain\Product\Services\PopularProduct\PopularProductService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class PopularProductListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public ?string $name = 'Популярные товары';

    public ?string $description = 'Раздел управления популярными товарами позволяет вам настроить,
    какие товары будут отображаться пользователям, если отсутствуют результаты поиска.
    Также товары из этого раздела будут отображаться в подборке "Часто покупают"';

    public function __construct(
        private readonly PopularProductService $popularProductService,
    ) {

    }

    public function query(): array
    {
        $popularProducts = PopularProduct::query()
            ->orderBy('sort')
            ->with(['product'])
            ->get();

        return [
            'popularProducts'  => $popularProducts,
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
            PopularProductListLayout::class,
        ];
    }

    public function save(PopularProductsRequest $request): RedirectResponse
    {
        $popularProducts = Arr::get($request->validated(), 'popularProducts', []);

        $this->popularProductService->updatePopularProducts($popularProducts);

        Toast::success('Список популярных товаров успешно сохранены');

        return redirect()->route('platform.popular-products');
    }

    public function clear(): RedirectResponse
    {
        $this->popularProductService->clearPopularProducts();

        Toast::success('Список популярных товаров успешно очищен');

        return redirect()->route('platform.popular-products');
    }
}
