<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Infrastructure\Services\Buyer\Components\BuyerAddressService;
use Infrastructure\Services\Buyer\Components\BuyerBasketTokenService;
use Infrastructure\Services\Buyer\Components\BuyerDeliveryCoordinatesService;
use Infrastructure\Services\Buyer\Components\BuyerDeliveryIntervalService;
use Infrastructure\Services\Buyer\Components\BuyerDeliverySubTypeService;
use Infrastructure\Services\Buyer\Components\BuyerDeliveryTypeService;
use Infrastructure\Services\Buyer\Components\BuyerTokenService;
use Infrastructure\Services\Buyer\Components\City\BuyerCityService;
use Infrastructure\Services\Buyer\Components\Store\BuyerStoreService;

class BuyerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('BuyerToken', function () {
            return new BuyerTokenService();
        });

        $this->app->singleton('BuyerDeliveryType', function () {
            return new BuyerDeliveryTypeService();
        });

        $this->app->singleton('BuyerDeliverySubType', function () {
            return new BuyerDeliverySubTypeService();
        });

        $this->app->singleton('BuyerCity', function () {
            return new BuyerCityService();
        });

        $this->app->singleton('BuyerStore', function () {
            return new BuyerStoreService();
        });

        $this->app->singleton('BuyerAddress', function () {
            return new BuyerAddressService();
        });

        $this->app->singleton('BuyerDeliveryInterval', function () {
            return new BuyerDeliveryIntervalService();
        });

        $this->app->singleton('BuyerDeliveryCoordinates', function () {
            return new BuyerDeliveryCoordinatesService();
        });

        $this->app->singleton('BuyerBasketToken', function () {
            return new BuyerBasketTokenService();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
