<?php

namespace Domain\Basket\Services\Products;

use Domain\Order\Services\Delivery\DateServices\ReceiveInterval;
use Domain\Product\Models\Product;
use Domain\Product\Services\Category\CategorySelection;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Infrastructure\Services\Buyer\Facades\BuyerStore;

//TODO переписать данный класс или удалить за ненадобностью
class DateSplitter
{
    private array $result = [];

    /**
     * Резделяет корзину по дням доставки, по совместительству - откидывает все не выбранные товары
     * @return Collection[]
     */
    public function splitByDeliveryDate(
        Collection $products,
        array $selection,
        string $date = null
    ): array {
        $specialCategories = CategorySelection::getSpecialCategoriesByProducts(
            $products->pluck('id1C')->toArray(),
        );

        foreach ($products as $product) {
            if (!empty($selection) && !in_array($product->id, $selection)){
                continue;
            }

            $isNewYearProduct = $specialCategories->where('product1CId', $product->getAttribute('system_id'))->isNotEmpty();

            if ($isNewYearProduct) {
                $cdate = Carbon::createFromFormat('Y-m-d', $date);
                $cdatePlus3D = Carbon::now()->addDays(3);
                if ($cdate < $cdatePlus3D) {
                    $date21 = Carbon::create(2022, 12, 21);
                    if ($cdatePlus3D < $date21) {
                        $xdate = $date21->format('Y-m-d');
                    } else {
                        $xdate = $cdatePlus3D->format('Y-m-d');
                    }
                } else {
                    $xdate = $cdate->format('Y-m-d');
                }
            } else {
                $xdate = $date;

                if($product->by_preorder){
                    if (count($product->nearest_delivery_dates) > 0) {
                        $xdate = Carbon::createFromFormat('Y-m-d', $product->nearest_delivery_dates[0])
                            ->format('Y-m-d');
                    } else {
                        continue;
                    }
                }
            }

            $this->putProductToBasketGroup($product, $xdate);
        }

        usort($this->result, function ($a, $b) {
            return strtotime($a['nearest_date']) <=> strtotime($b['nearest_date']);
        });

        return $this->result;
    }

    private function putProductToBasketGroup(Product $product, $date = null): void
    {
        $this->initResultGroup($product, $date ?? Carbon::now()->format('Y-m-d'));
    }

    private function setByNow(Product $product): void
    {
        $store = BuyerStore::getSelectedStore();
        $now = Carbon::now()->startOfDay();

        $intervalMaker = new ReceiveInterval($store);
        $intervals = $intervalMaker->getInterval($now);

        $date = Arr::get($intervals, '0.date', $now->format('Y-m-d'));

        $this->initResultGroup($product, $date);
    }

    private function initResultGroup(Product $product, string $nearestDate): void
    {
        $byPreOrder = $product->by_preorder;

        foreach ($this->result as $key => $item) {
            if ($item['nearest_date'] === $nearestDate) {
                $this->result[$key] = $this->addProductToGroup($product, $item);
                return;
            }
        }

        $group = [
            'nearest_date' => $nearestDate,
            'by_preorder' => $byPreOrder,
            'products' => new Collection()
        ];

        $this->result[] = $this->addProductToGroup($product, $group);
    }

    private function addProductToGroup(Product $product, array $group): array
    {
        $hasProduct = false;

        $group['products'] = $group['products']->map(function (Product $p) use ($product, &$hasProduct) {
            if ($p->id === $product->id) {
                $hasProduct = true;
                $p->pivot->count += $product->count;
            }

            return $p;
        });

        if (!$hasProduct) {
            $group['products']->add($product);
        }

        return $group;
    }

    private function setByNearestDate(Product $product)
    {
        // ближайшая дата доставки/поставки
        if ($product->by_preorder) {
            $nearestDate = Arr::first($product->nearest_delivery_dates);
        } else {
            $nearestDate = $product->date_supply;
        }

        if ($nearestDate) {
            $this->initResultGroup($product, $nearestDate);
        } elseif ($product->pivot->from_order) {
            $this->setByNow($product);
        }
    }
}
