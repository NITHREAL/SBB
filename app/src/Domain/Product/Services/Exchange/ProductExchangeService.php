<?php

declare(strict_types=1);

namespace Domain\Product\Services\Exchange;

use Domain\Product\DTO\Exchange\ProductExchangeDTO;
use Domain\Product\Models\Product;

class ProductExchangeService
{
    public function exchange(ProductExchangeDTO $productExchangeDTO): Product
    {
        $product = $this->updateOrCreateProduct($productExchangeDTO);
        $this->syncCategories($product, $productExchangeDTO->getCategories1cId());
        $this->updateDeliveryDates($product, $productExchangeDTO->getDeliveryDates());

        return $product;
    }

    private function updateOrCreateProduct(ProductExchangeDTO $productExchangeDTO): Product
    {
        $data = $productExchangeDTO->toArray();
        $data['show_as_preorder'] = $productExchangeDTO->isByPreorder() ;
        $data['by_preorder'] = $productExchangeDTO->isByPreorder();
        $data['delivery_in_country'] = true;
        $data['cooking'] = false;
        $data['farmer_system_id'] = "00000000-0000-0000-0000-000000000000";
        $data['is_ready_to_eat'] = $productExchangeDTO->isReadyToEat();

        $product = Product::query()->where('system_id', $data['system_id'])->first() ?? new Product();

        $product->fill($data);

        $product->save();

        return $product;
    }

    private function syncCategories(Product $product, array $categories): void
    {
        $product->categories()->sync($categories);
    }

    private function updateDeliveryDates(Product $product, array $deliveryDates): void
    {
        $product->deliveryDates()->delete();

        foreach ($deliveryDates as $deliveryDate) {
            $product->deliveryDates()->create(['date' => $deliveryDate]);
        }
    }
}
