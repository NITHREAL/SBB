<?php

namespace Infrastructure\Services\Buyer\Components\City;

use Domain\City\Models\City;
use Infrastructure\Services\Buyer\BuyerDataService;
use Infrastructure\Services\Buyer\Components\City\Helper\CityEntityHelper;

class BuyerCityService extends BuyerDataService
{
    protected const ATTRIBUTE_CACHE_KEY = 'city_id';

    protected CityEntityHelper $cityEntityHelper;

    public function __construct() {
        parent::__construct();

        $this->cityEntityHelper = app()->make(CityEntityHelper::class);
    }

    public function getSelectedCity(): ?City
    {
        $cityId = (int) $this->getValue();

        return City::find($cityId);
    }

    protected function getDefaultValue(): string
    {
        $city = $this->cityEntityHelper->getDefaultCity() ?? City::first();

        return $city->id;
    }
}
