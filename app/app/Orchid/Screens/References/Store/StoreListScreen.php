<?php

namespace App\Orchid\Screens\References\Store;

use App\Orchid\Layouts\JsAutoUpdateLayout;
use App\Orchid\Layouts\References\Store\StoreFilterLayout;
use App\Orchid\Layouts\References\Store\StoreListLayout;
use Domain\Store\Models\Store;
use Illuminate\Http\Request;
use Orchid\Screen\Screen;

class StoreListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public ?string $name = 'Список магазинов';


    /**
     * Display header description.
     *
     * @var string|null
     */
    public function description(): ?string
    {
        return __('admin.store.stores_description');
    }

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

        $query = Store::filtersApplySelection(StoreFilterLayout::class)
            ->with('city')
            ->filters();

        if ($column === 'city.title') {
            $query->leftJoin('cities', 'stores.city_id', '=', 'cities.id')
                ->orderBy('cities.title', $direction);
        } else {
            $query->orderBy($column, $direction);
        }

        return [
            'stores' => $query->paginate()
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
            StoreFilterLayout::class,
            StoreListLayout::class,
            JsAutoupdateLayout::class,
        ];
    }

    public function activate(Request $request): void
    {
        Store::findOrFail($request->get('id'))->activate($request->get('activate', false));
    }
}
