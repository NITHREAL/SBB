<?php

namespace App\Orchid\Screens\Shop\AbandonedBaskets;

use App\Orchid\Layouts\Shop\AbandonedBaskets\AbandonedBasketsSettingLayout;
use Domain\Basket\Models\Basket;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Infrastructure\Http\Requests\Admin\AbandonedBasketsRequest;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;

class AbandonedBasketsSettingScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public string $name = 'Сформировать отчет "Брошенные корзины"';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [];
    }

    public function create(AbandonedBasketsRequest $request): Redirector|Application|RedirectResponse
    {
        $filter = $request->all();
        $parametersData = [
            'hours' => $filter['abandoned_over_in_hours'] ?? 0,
            'user_id' => $filter['user_id'] ?? null,
            'summ_min' => $filter['summ_min'] ?? null,
            'summ_max' => $filter['summ_max'] ?? null,
            'available' => $filter['available'] ?? null,
            'created_at' => [
                'start' => $filter['created_at']['start'] ?? null,
                'end' => $filter['created_at']['end'] ?? null
            ],
            'updated_at' => [
                'start' => $filter['updated_at']['start'] ?? null,
                'end' => $filter['updated_at']['end'] ?? null
            ]
        ];

        if (!empty($filter['product_id'])) {
            if (!is_array($filter['product_id'])) {
                $filter['product_id'] = [$filter['product_id']];
            }
            $baskets = Basket::join('basket_product', 'baskets.id', '=', 'basket_product.basket_id')
                ->whereIn('basket_product.product_id', $filter['product_id'])
                ->whereNotNull('baskets.user_id')
                ->get();

            foreach ($baskets as $basket) {
                $parametersData['baskets'][] .= $basket->id;
            }
        }

        return redirect('/abandoned-baskets?'.http_build_query($parametersData));
    }

    /**
     * Views.
     *
     * @return string[]
     */
    public function layout(): array
    {
        return [
            Layout::block(AbandonedBasketsSettingLayout::class)
                ->title('Настройки отчета "Брошенные корзины"')
                ->commands(
                    Button::make("Сформировать")
                        ->type(Color::DEFAULT())
                        ->icon('check')
                        ->method('create')
                )
        ];
    }
}
