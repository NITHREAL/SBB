<?php

namespace App\Orchid\Screens\Shop\ForgottenProduct;

use App\Orchid\Layouts\Shop\ForgottenProduct\ForgottenProductListLayout;
use Domain\Product\Models\ForgottenProduct;
use Domain\Product\Requests\Admin\ForgottenProduct\ForgottenProductsRequest;
use Domain\Product\Services\ForgottenProduct\ForgottenProductService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class ForgottenProductListScreen extends Screen
{
    public ?string $name = 'Не забудьте купить';

    public ?string $description = 'Раздел управления товарами блока "Не забудьте купить" позволяет вам настроить товары,
     которые будут отображаться пользователям в соответствующем разделе';

    public function __construct(
        private readonly ForgottenProductService $forgottenProductService,
    ) {
    }

    public function query(): array
    {
        $forgottenProducts = ForgottenProduct::query()
            ->orderBy('sort')
            ->with(['product'])
            ->get();

        return [
            'forgotten_products'  => $forgottenProducts,
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
            ForgottenProductListLayout::class,
        ];
    }

    public function save(ForgottenProductsRequest $request): RedirectResponse
    {
        $forgottenProducts = Arr::get($request->validated(), 'forgottenProducts', []);

        $this->forgottenProductService->updateForgottenProducts($forgottenProducts);

        Toast::success('Список товаров раздела "Не забудьте купить" успешно сохранен');

        return redirect()->route('platform.forgotten-products');
    }

    public function clear(): RedirectResponse
    {
        $this->forgottenProductService->clearPopularProducts();

        Toast::success('Список товаров раздела "Не забудьте купить" успешно очищен');

        return redirect()->route('platform.forgotten-products');
    }
}
