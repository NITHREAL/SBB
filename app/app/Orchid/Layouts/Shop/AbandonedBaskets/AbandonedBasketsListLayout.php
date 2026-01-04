<?php

namespace App\Orchid\Layouts\Shop\AbandonedBaskets;

use App\Orchid\Helpers\TD\ID;
use App\Orchid\Helpers\TD\DateTime;
use Domain\Basket\Models\Basket;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class AbandonedBasketsListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'abandoned_baskets';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): array
    {
        return [
            ID::make('id', "ID корзины"),
            ID::make('user_id', "ID пользователя"),
            TD::make('user.phone', __('admin.user.phone'))
                ->render(function (Basket $basket) {
                    $user = $basket->user;
                    if (empty($user?->phone)) {
                        return "Не авторизован";
                    }
                    return Link::make("+7".$user?->phone)
                        ->route('platform.systems.users.edit', $user?->id);
                }),
            TD::make('user.name', __('admin.user.full_name'))
                ->render(function (Basket $basket) {
                    return $basket->user?->full_name;
                }),
            TD::make('basket.products_total_price', 'Сумма, руб.')
                ->render(function (Basket $basket) {
                    $total = 0;
                    $store1cId = $basket->user?->store_system_id;
                    if (empty($store1cId)) {
                        return "Не указал город";
                    }
                    foreach ($basket->products as $product) {
                        $price = $product->stores()->wherePivot('store_system_id', $store1cId)->first()->pivot->price;
                        $count = $product->pivot->count;
                        $total += ($price * $count);
                    }
                    return $total;
                }),
            TD::make('basket.products_count', 'Количество наименований')
                ->render(function (Basket $basket) {
                    return $basket->products()->count();
                }),
            TD::make('basket.available', 'Наличие товаров')
                ->render(function (Basket $basket) {
                    $available = "";
                    $store1cId = $basket->user?->store_system_id;
                    if (empty($store1cId)) {
                        return "Не указал город";
                    }
                    foreach ($basket->products as $product) {
                        $productStorePivot = $product->stores()->wherePivot('store_system_id', $store1cId)->first()->pivot;
                        $count = $productStorePivot->count;
                        $byPreorder = $productStorePivot->by_preorder;
                        if ($available == "Нет в наличии") {
                            continue;
                        } elseif ($count > 0) {
                            $available = "В наличии";
                        } elseif ($byPreorder == 1) {
                            $available = "По предзаказу";
                        } else {
                            $available = "Нет в наличии";
                        }
                    }
                    return $available;
                }),
            DateTime::createdAt(),
            DateTime::updatedAt(),
            TD::make('basket.products', 'Наименования')
                ->render(function (Basket $basket) {
                    $productTitles = "";
                    foreach ($basket->products as $product) {
                        $productTitles .= $product->title.", <br/>";
                    }
                    return $productTitles;
                }),

        ];
    }
}
