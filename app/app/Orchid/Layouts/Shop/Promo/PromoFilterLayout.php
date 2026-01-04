<?php

namespace App\Orchid\Layouts\Shop\Promo;

use App\Orchid\Filters\ActiveFilter;
use App\Orchid\Filters\IdFilter;
use App\Orchid\Filters\Promo\PromoCodeFilter;
use App\Orchid\Filters\Promo\PromoDiscountFilter;
use App\Orchid\Filters\Promo\PromoExpireFilter;
use App\Orchid\Filters\Promo\PromoFreeDeliveryFilter;
use Orchid\Screen\Layouts\Selection;

class PromoFilterLayout extends Selection
{
    public function filters(): array
    {
        return [
            IdFilter::class,
            ActiveFilter::class,
            PromoCodeFilter::class,
            PromoDiscountFilter::class,
            PromoExpireFilter::class,
            PromoFreeDeliveryFilter::class
        ];
    }
}
