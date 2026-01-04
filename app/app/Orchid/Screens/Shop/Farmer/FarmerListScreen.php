<?php

namespace App\Orchid\Screens\Shop\Farmer;

use App\Orchid\Layouts\Shop\Farmer\FarmerFiltersLayout;
use App\Orchid\Layouts\Shop\Farmer\FarmerListLayout;
use Domain\Farmer\Models\Farmer;
use Illuminate\Http\Request;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class FarmerListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Список фермеров';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'farmers' => Farmer::filtersApplySelection(FarmerFiltersLayout::class)
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
            FarmerFiltersLayout::class,
            FarmerListLayout::class
        ];
    }

    public function activate(Request $request)
    {
        $id = $request->get('id');
        $activate = $request->get('activate', false);

        $farmer = Farmer::findOrFail($id);

        $farmer->active = $activate;
        $farmer->save();

        Toast::success('Активность успешно изменена');
    }
}
