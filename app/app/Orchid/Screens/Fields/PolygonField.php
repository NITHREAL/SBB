<?php

namespace App\Orchid\Screens\Fields;

use Domain\Store\Models\Store;
use Orchid\Screen\Field;

/**
 * Class PolygonField.
 */
class PolygonField extends Field
{
    protected $view = 'fields.polygon_field';

    /**
     * All attributes that are available to the field.
     *
     * @var array
     */
    protected $attributes = [
        'value' => null,
        'apiKey' => '',
        'store' => null,
        'otherPolygons' => null,
        'types' => null,
    ];

    /**
     * Code constructor.
     */
    public function __construct()
    {
        $this->addBeforeRender(function () {
            $this->set('apiKey', $this->getApiKey());
        });
    }

    public function setStore(Store $store): Field|PolygonField
    {
        return $this->set('store', $store);
    }

    public function setOtherPolygons($otherPolygons): Field|PolygonField
    {
        return $this->set('otherPolygons', $otherPolygons);
    }

    public function setTypes($types): Field|PolygonField
    {
        return $this->set('types', $types);
    }

    public function getApiKey()
    {
        return config('platform.yandex_map_api_key');
    }

    public function setDescription($description): Field|PolygonField
    {
        return $this->set('description', $description);
    }
}

