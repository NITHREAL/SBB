<?php

namespace App\Orchid\Screens\Fields;

use Orchid\Screen\Fields\Input;

/**
 * Class CityField.
 */
class CityField extends Input
{
    protected $view = 'fields.city_field';

    /**
     * @param array $datalist
     *
     * @return CityField
     */
    public function datalist(array $datalist = []): self
    {
        if (empty($datalist)) {
            $this->set('datalist', $datalist);
        }

        return $this->addBeforeRender(function () {
            $this->set('list', 'datalist-'.$this->get('name'));
        });
    }
}
