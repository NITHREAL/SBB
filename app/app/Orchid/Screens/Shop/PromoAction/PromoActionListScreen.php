<?php

namespace App\Orchid\Screens\Shop\PromoAction;

use App\Orchid\Layouts\Shop\Group\GroupFiltersLayout;
use App\Orchid\Layouts\Shop\PromoAction\PromoActionFiltersLayout;
use App\Orchid\Layouts\Shop\PromoAction\PromoActionListLayout;
use Domain\PromoAction\Models\PromoAction;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class PromoActionListScreen extends Screen
{
    public function name(): string
    {
        return 'Список промо акций';
    }

    public function query(): array
    {
        return [
            'promoActions'  => PromoAction::filtersApplySelection(GroupFiltersLayout::class)
                ->filters()
                ->withCount('products')
                ->defaultSort('sort')
                ->paginate(),
        ];
    }

    public function commandBar(): array
    {
        return [
            Link::make(__('admin.create'))
                ->icon('plus')
                ->route('platform.promo-actions.create'),
        ];
    }

    public function layout(): array
    {
        return [
            PromoActionFiltersLayout::class,
            PromoActionListLayout::class,
        ];
    }

    public function activate(Request $request): void
    {
        $activate = (bool)$request->get('activate', false);
        $id = $request->get('id');

        $promoAction = PromoAction::findOrFail($id);

        $promoAction->active = $activate;
        $promoAction->save();

        Toast::success('Активность успешно изменена');
    }
}
