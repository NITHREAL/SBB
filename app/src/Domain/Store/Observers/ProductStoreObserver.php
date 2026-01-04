<?php

namespace Domain\Store\Observers;

use Domain\Store\Jobs\Picker\PublishProductStoreToAmqp as PublishToPicker;
use Domain\Store\Jobs\Courier\PublishProductStoreToAmqp as PublishToCourier;
use Domain\Store\Models\ProductStore;

class ProductStoreObserver
{
    public function __construct(
    ) {
    }

    public function saved(ProductStore $productStore): void
    {
        PublishToPicker::dispatch($productStore);
        PublishToCourier::dispatch($productStore);
    }
}
