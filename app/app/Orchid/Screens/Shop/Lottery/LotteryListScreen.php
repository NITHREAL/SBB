<?php

namespace App\Orchid\Screens\Shop\Lottery;

use App\Orchid\Layouts\Shop\Group\GroupFiltersLayout;
use App\Orchid\Layouts\Shop\Lottery\LotteryFiltersLayout;
use App\Orchid\Layouts\Shop\Lottery\LotteryListLayout;
use Domain\Lottery\Models\Lottery;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class LotteryListScreen extends Screen
{
    public function name(): string
    {
        return 'Список розыгрышей';
    }

    public function query(): array
    {
        return [
            'lotteries' => Lottery::filtersApplySelection(GroupFiltersLayout::class)
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
                ->route('platform.lotteries.create'),
        ];
    }

    public function layout(): array
    {
        return [
            LotteryFiltersLayout::class,
            LotteryListLayout::class,
        ];
    }

    public function activate(Request $request): void
    {
        $activate = (bool)$request->get('activate', false);
        $id = $request->get('id');

        $lottery = Lottery::findOrFail($id);

        $lottery->active = $activate;
        $lottery->save();

        Toast::success('Активность успешно изменена');
    }

}
