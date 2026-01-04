<?php

namespace App\Orchid\Screens\Shop\WeekProduct;

use App\Orchid\Layouts\Shop\WeekProduct\WeekProductListLayout;
use Domain\Product\Models\WeekProduct;
use Domain\Product\Requests\Admin\WeekProduct\WeekProductsRequest;
use Domain\Product\Services\WeekProduct\WeekProductService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class WeekProductListScreen extends Screen
{
    /**
     * The name of the screen displayed in the header.
     */
    public function name(): ?string
    {
        return 'Товар недели';
    }

    /**
     * Display header description.
     */
    public function description(): ?string
    {
        return 'Данный раздел управления позволяет вам настроить,
         какие товары будут отображаться пользователям в подборке "Товар недели"';
    }

    public function __construct(
        private readonly WeekProductService $weekProductService,
    ) {

    }

    public function query(): array
    {
        $weekProducts = WeekProduct::query()
            ->orderBy('sort')
            ->with(['product'])
            ->get();

        return [
            'weekProducts'  => $weekProducts,
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
            WeekProductListLayout::class,
        ];
    }

    public function save(WeekProductsRequest $request): RedirectResponse
    {
        $weekProducts = Arr::get($request->validated(), 'weekProducts', []);

        $this->weekProductService->updateWeekProducts($weekProducts);

        Toast::success('Список товаров недели успешно сохранены');

        return redirect()->route('platform.week-products');
    }

    public function clear(): RedirectResponse
    {
        $this->weekProductService->clearWeekProducts();

        Toast::success('Список товаров недели успешно очищен');

        return redirect()->route('platform.week-products');
    }
}
