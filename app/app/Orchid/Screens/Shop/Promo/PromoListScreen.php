<?php

namespace App\Orchid\Screens\Shop\Promo;

use App\Orchid\Core\Actions;
use App\Orchid\Layouts\Shop\Promo\PromoFilterLayout;
use App\Orchid\Layouts\Shop\Promo\PromoListLayout;
use Domain\Promocode\Models\Promocode;
use Illuminate\Http\Request;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class PromoListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public string $name = 'Список промокодов';

    public function commandBar(): array
    {
        return Actions::make([
            new Actions\Create('platform.promos.create'),
            new Actions\Export('promo')
        ]);
    }

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'promos' => Promocode::filtersApplySelection(PromoFilterLayout::class)
                ->filters()
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
            PromoFilterLayout::class,
            PromoListLayout::class
        ];
    }

    public function activate(Promocode $promocode, Request $request): void
    {
        $activate = (bool)$request->get('activate', false);

        $promocode->active = $activate;
        $promocode->save();

        Toast::success('Активность успешно изменена');
    }

    public function mobile(Request $request): void
    {
        $activate = $request->get('activate', false);
        $id = $request->get('id');

        $promocode = Promocode::findOrFail($id);

        $promocode->mobile = $activate;
        $promocode->save();

        Toast::success('Активность успешно изменена');
    }
}
