<?php

namespace App\Orchid\Screens\References\Region;

use App\Orchid\Layouts\References\Region\RegionFilterLayout;
use App\Orchid\Layouts\References\Region\RegionListLayout;
use Domain\City\Models\Region;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class RegionListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Список регионов';

    /**
     * Display header description.
     *
     * @var string|null
     */
    public $description = null;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'regions' => Region::filtersApplySelection(RegionFilterLayout::class)
                ->filters()
                ->defaultSort('id', 'desc')
                ->paginate()
        ];
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): array
    {
        return [
            Link::make(__('admin.create'))
                ->icon('plus')
                ->route('platform.regions.create')
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
            RegionFilterLayout::class,
            RegionListLayout::class
        ];
    }
}
