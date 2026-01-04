<?php

namespace Domain\Product\DTO\Exchange\Courier;

use Domain\Image\Helpers\ImagePropertiesHelper;
use Domain\Product\Models\Product;
use Infrastructure\DTO\BaseDTO;

class ProductDTO extends BaseDTO
{
    public function __construct(
        public ?string $systemId,
        public ?string $farmerSystemId,
        public ?string $unitSystemId,
        public ?bool $active,
        public ?string $sku,
        public ?string $title,
        public ?string $slug,
        public ?string $description,
        public ?string $composition,
        public ?string $storageConditions,
        public ?float $proteins,
        public ?float $fats,
        public ?float $carbohydrates,
        public ?float $nutritionKcal,
        public ?float $nutritionKj,
        public ?float $weight,
        public ?string $shelfLife,
        public ?bool $byPreorder,
        public ?bool $cooking,
        public ?string $type,
        public ?float $unitQuantum,
        public ?string $imageOriginal,
    ) {
    }

    public static function fromModel(Product $product): self
    {
        $productImage = $product->images()->first();

        if ($productImage) {
            $productImage = ImagePropertiesHelper::setImageProperties(
                $product, $product->images()->first()
            )->image_original;
        }

        return new self(
            systemId: $product->system_id,
            farmerSystemId: $product->farmer_system_id,
            unitSystemId: $product->unit_system_id,
            active: $product->active,
            sku: $product->sku,
            title: $product->title,
            slug: $product->slug,
            description: $product->description,
            composition: $product->composition,
            storageConditions: $product->storage_conditions,
            proteins: $product->proteins,
            fats: $product->fats,
            carbohydrates: $product->carbohydrates,
            nutritionKcal: $product->nutrition_kcal,
            nutritionKj: $product->nutrition_kj,
            weight: $product->weight,
            shelfLife: $product->shelf_life,
            byPreorder: $product->by_preorder,
            cooking: $product->cooking,
            type: $product->type,
            unitQuantum: $product->unit_quantum,
            imageOriginal: $productImage,
        );
    }
}
