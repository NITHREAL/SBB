<?php

namespace Domain\Store\Observers;

use Domain\Store\Jobs\Picker\PublishStoreToAmqp as PublishToPicker;
use Domain\Store\Jobs\Courier\PublishStoreToAmqp as PublishToCourier;
use Domain\Store\Models\Store;

class StoreObserver
{
    public function __construct(
    ) {
    }

    public function saved(Store $store): void
    {
        PublishToPicker::dispatch($store);
        PublishToCourier::dispatch($store);
    }
}
