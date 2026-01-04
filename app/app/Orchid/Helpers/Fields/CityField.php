<?php

namespace App\Orchid\Helpers\Fields;

use Domain\City\Models\City;
use Orchid\Screen\Fields\Select;

class CityField extends Select
{
    public static function make(?string $name = 'title'): self
    {
        $cityField = parent::make($name);

        $cityField->empty('Выберите населенный пункт')
            ->horizontal();

        $cities = City::with('region')->orderBy('title')->get();
        $options = [];

        foreach ($cities as $city) {
            $options[$city->id] = $city->title . ', ' . $city->region->title;
        }

        $cityField->set('options', $options);

        $cityField->addBeforeRender(function () {
            $value = [];

            collect($this->get('value'))->each(static function ($item) use (&$value) {
                if (is_object($item)) {
                    $value[$item->id] = $item->title . ', ' . $item->region->title;
                } else {
                    $value[] = $item;
                }
            });

            $this->set('value', $value);
        });

        return $cityField;
    }
}
