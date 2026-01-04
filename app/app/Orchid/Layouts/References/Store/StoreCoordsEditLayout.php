<?php

namespace App\Orchid\Layouts\References\Store;

use Orchid\Screen\Field;
use Orchid\Screen\Fields\Map;
use Orchid\Screen\Layouts\Rows;

class StoreCoordsEditLayout extends Rows
{
    private const LNG_MOSCOW = 37.6173;
    private const LAT_MOSCOW = 55.7558;


    protected $title = 'Объект на карте';

    /**
     * Get the fields elements to be displayed.
     *
     * @return Field[]
     */
    protected function fields(): array
    {
        $store = $this->query['store'];

        return [
            Map::make('coords')
                ->title(__('admin.store.map'))
                ->height('500px')
                ->value([
                    'lat' => $store->latitude ?? self::LAT_MOSCOW,
                    'lng' => $store->longitude ?? self::LNG_MOSCOW,
                ])
                ->help(__('admin.store.search_help'))
        ];
    }
}
