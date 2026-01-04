<?php

namespace Domain\Store\DTO\Exchange\Courier;

use Domain\Store\Models\Store;
use Infrastructure\DTO\BaseDTO;

class StoreDTO extends BaseDTO
{
    public function __construct(
        public ?int $id,
        public ?string $setId,
        public ?string $systemId,
        public ?bool $active,
        public ?string $title,
        public ?string $address,
        public ?string $workTime,
        public ?float $latitude,
        public ?float $longitude,
        public ?string $slug,
        public ?bool $isDarkStore,
    ) {
    }

    public static function fromModel(Store $store): self
    {
        return new self(
            id: $store->id,
            setId: $store->set_id,
            systemId: $store->system_id,
            active: $store->active,
            title: $store->title,
            address: $store->address,
            workTime: $store->work_time,
            latitude: $store->latitude,
            longitude: $store->longitude,
            slug: $store->slug,
            isDarkStore: $store->is_dark_store,
        );
    }
}
