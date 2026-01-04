<?php

namespace App\Providers;

use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Infrastructure\Services\Acquiring\AcquiringService;
use Infrastructure\Services\Acquiring\Enums\AcquiringTypeEnum;
use Infrastructure\Services\Acquiring\Gateways\Sberbank;
use Infrastructure\Services\Acquiring\Gateways\Yookassa;

class AcquiringServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind('Acquiring', function () {
            $acquiringParams = config('api.acquiring');
            $acquiringType = Arr::get($acquiringParams, 'type');
            $acquiringDebug = Arr::get($acquiringParams, 'debug');

            switch ($acquiringType) {
                case 'sberbank':
                    $sberbankConfig = config('services.sberbank');

                    $gateway = new Sberbank($sberbankConfig);
                    break;
                case AcquiringTypeEnum::yookassa()->value:
                default:
                    $config = config('services.yookassa');
                    $gateway = new Yookassa($config);
                    break;
            }

            if ($acquiringDebug) {
                $gateway->debug();
            }

            return new AcquiringService($gateway);
        });
    }
}
