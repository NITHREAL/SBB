<?php

namespace App\Orchid\Screens\FarmerCounter;

use App\Models\Counter;
use App\Orchid\Layouts\FarmerCounter\FarmerCounterListLayout;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class FarmerCounterListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Счетчик займов фермерам';

    /**
     * Button commands.
     *
     * @return Action[]
     */
    public function commandBar(): array
    {
        return [
            Link::make(__('admin.create'))
                ->icon('plus')
                ->route('platform.farmer-counter.create')
        ];
    }

    /**
     * Query data.
     * @return array
     */
    public function query(): array
    {
        return [
            'farmer_counter' => Counter::query()
                ->where('type', 'farmer')
                ->orderBy('sort')
                ->get()
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
            FarmerCounterListLayout::class,
        ];
    }
}
