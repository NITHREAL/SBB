<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Infrastructure\Services\Loyalty\Enums\LoyaltyTypeEnum;
use Infrastructure\Services\Loyalty\Gateways\Manzana\Manzana;
use Infrastructure\Services\Loyalty\LoyaltyService;
use Infrastructure\Services\LoyaltySystem\FakeLoyaltyService;

class LoyaltyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('Loyalty', function () {
            $loyaltyType = config('api.loyalty.type');
            $configData = config('services.manzana');

            $gate =  match($loyaltyType) {
                LoyaltyTypeEnum::manzana()->value   => new Manzana($configData),
                default                             => new FakeLoyaltyService(),
            };

            return new LoyaltyService($gate);
        });
    }
}
