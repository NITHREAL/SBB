<?php

namespace Infrastructure\Services\Buyer\Facades;

use Domain\Store\Models\Store;
use Illuminate\Support\Facades\Facade;
use Infrastructure\Services\Buyer\Components\Store\BuyerStoreService;

/**
 * @mixin BuyerStoreService
 * @method array getValue()
 * @method void setValue(string $value)
 * @method Store|null getSelectedStore()
 * @method int getId(array $storeData = null)
 * @method string getOneCId(array $storeData = null)
 * @method string getTitle(array $storeData = null)
 * @method string getAddress(array $storeData = null)
 * @method string getLatitude(array $storeData = null)
 * @method string getLongitude(array $storeData = null)
 * @method int getCityId(array $storeData = null)
 */
class BuyerStore extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'BuyerStore';
    }
}
