<?php

namespace Tests;

use Infrastructure\Services\Buyer\Facades\BuyerStore;

class BuyerTestHelper
{
    public static function getSelectedStoreBuyerStore($store): void
    {
        BuyerStore::shouldReceive('getSelectedStore')
            ->andReturn($store);
    }

    public static function getValueBuyerStore($store): void
    {
        BuyerStore::shouldReceive('getValue')
            ->andReturn(
                [
                    'id'        => $store->getAttribute('id'),
                    '1c_id'     => $store->getAttribute('1c_id'),
                    'title'     => $store->getAttribute('title'),
                    'city_id'   => $store->getAttribute('city_id'),
                    'address'   => $store->getAttribute('address'),
                    'latitude'  => $store->getAttribute('latitude'),
                    'longitude' => $store->getAttribute('longitude'),
                ]
            );
    }

    public static function getIdBuyerStore($store): void
    {
        BuyerStore::shouldReceive('getId')
            ->andReturn($store->getAttribute('id'));
    }

    public static function getOneCIdStoreBuyerStore($store): void
    {
        BuyerStore::shouldReceive('getOneCId')
            ->andReturn($store->getAttribute('system_id'));
    }

    public static function setValueBuyerStore($store)
    {
        BuyerStore::shouldReceive('setValue')
            ->andReturn($store->getAttribute('id'));
    }
}
