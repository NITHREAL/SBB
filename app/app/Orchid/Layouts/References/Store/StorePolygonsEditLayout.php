<?php

namespace App\Orchid\Layouts\References\Store;

use App\Orchid\Screens\Fields\PolygonField;
use Domain\Order\Enums\Delivery\PolygonDeliveryTypeEnum;
use Orchid\Screen\Field;
use Orchid\Screen\Layouts\Rows;

class StorePolygonsEditLayout extends Rows
{
    protected $title = 'Полигон';

    /**
     * Get the fields elements to be displayed.
     *
     * @return Field[]
     */
    protected function fields(): array
    {
        $store = $this->query['store'];
        $otherPolygons = $this->query['otherPolygons'];

        return [
            PolygonField::make('polygons')
                ->value($store->polygons)
                ->setStore($store)
                ->setOtherPolygons($otherPolygons)
                ->setTypes(collect(PolygonDeliveryTypeEnum::toArray())),
        ];
    }
}
