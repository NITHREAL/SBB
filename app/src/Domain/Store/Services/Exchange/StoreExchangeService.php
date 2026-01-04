<?php

declare(strict_types=1);

namespace Domain\Store\Services\Exchange;

use Domain\Store\DTO\Exchange\StoreExchangeDTO;
use Domain\Store\Models\Store;

class StoreExchangeService
{
    public function exchange(StoreExchangeDTO $storeDTO): Store
    {
        $store = Store::query()->where('system_id', $storeDTO->getSystemId())->first() ?? new Store();

        $store->fill($storeDTO->toArray());

        $store->save();

        return $store;
    }
}

