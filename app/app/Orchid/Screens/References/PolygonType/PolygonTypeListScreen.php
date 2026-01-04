<?php

namespace App\Orchid\Screens\References\PolygonType;

use App\Orchid\Layouts\References\PolygonType\PolygonTypeListLayout;
use Domain\Order\Models\Delivery\PolygonType;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

class PolygonTypeListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public string $name = '';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        $this->name = __('admin.polygon_types');

        return [
            'polygon_types' => PolygonType::query()
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
        return [];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): array
    {
        return [
            Layout::view('components.polygon-description'),
            PolygonTypeListLayout::class
        ];
    }
}
