<?php

namespace App\Orchid\Screens\Shop\AbandonedBaskets;

use App\Orchid\Core\Actions;
use App\Orchid\Layouts\Shop\AbandonedBaskets\AbandonedBasketsFilterLayout;
use App\Orchid\Layouts\Shop\AbandonedBaskets\AbandonedBasketsListLayout;
use Domain\Basket\Models\Basket;
use Domain\Basket\Services\AbandonedBaskets\AbandonedBasketService;
use Orchid\Screen\Screen;

class AbandonedBasketsListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public string $name = 'Брошенные корзины';

    public function __construct(
        private readonly AbandonedBasketService $abandonedBasketService,
    ) {
    }

    public function commandBar(): array
    {
        $filter = request()->all();

        return Actions::make([
            new Actions\Export('abandoned_baskets', $filter),
        ]);
    }

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        $hours = request()->get('hours') ?? 0;

        $this->name = $hours > 0 ? $this->name . ' (более '.$hours.' часов)' : $this->name;
        $baskets = $this->abandonedBasketService->getCollection(1000);

        return [
            'abandoned_baskets' => Basket::filtersApplySelection(AbandonedBasketsFilterLayout::class)
                ->with('user')
                ->whereIn('id', $baskets->pluck('id'))
                ->defaultSort('id', 'desc')
                ->paginate()
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
            AbandonedBasketsFilterLayout::class,
            AbandonedBasketsListLayout::class
        ];
    }
}
