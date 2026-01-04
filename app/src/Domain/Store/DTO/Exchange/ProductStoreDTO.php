<?php

namespace Domain\Store\DTO\Exchange;

use Domain\Store\Models\ProductStore;
use Infrastructure\DTO\BaseDTO;

class ProductStoreDTO extends BaseDTO
{
    public function __construct(
        public ?string $hash,
        public ?string $productSystemId,
        public ?string $storeSystemId,
        public ?int $price,
        public ?int $priceDiscount,
        public ?int $count,
    ) {
    }

    public static function fromModel(ProductStore $productStore): self
    {
        return new self(
            hash: $productStore->hash,
            productSystemId: $productStore->product_system_id,
            storeSystemId: $productStore->store_system_id,
            price: $productStore->price,
            priceDiscount: $productStore->price_discount,
            count: $productStore->count,
        );
    }
}
