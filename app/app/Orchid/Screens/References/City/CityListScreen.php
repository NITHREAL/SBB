<?php

namespace App\Orchid\Screens\References\City;

use App\Orchid\Layouts\References\City\CityFiltersLayout;
use App\Orchid\Layouts\References\City\CityListLayout;
use Domain\City\Models\City;
use Illuminate\Support\Facades\Cache;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class CityListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Список городов';

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
        $sort = request('sort', 'id');
        $direction = str_starts_with($sort, '-') ? 'desc' : 'asc';
        $column = ltrim($sort, '-');

        $query = City::filtersApplySelection(CityFiltersLayout::class)
            ->select('cities.*')
            ->with('region')
            ->filters();

        if ($column === 'region.title') {
            $query->leftJoin('regions', 'cities.region_id', '=', 'regions.id')
                ->orderBy('regions.title', $direction);
        } else {
            $query->orderBy('cities.' . $column, $direction);
        }

        return [
            'cities' => $query->paginate(),
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
                ->route('platform.cities.create')
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
            CityFiltersLayout::class,
            CityListLayout::class
        ];
    }
}
