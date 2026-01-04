<?php

namespace Domain\Product\Observers;

use Domain\Product\Jobs\Courier\PublishProductToAmqp as PublishToCourier;
use Domain\Product\Jobs\Picker\PublishProductToAmqp as PublishToPicker;
use Domain\Product\Models\Product;

class ProductObserver
{
    public function saved(Product $product): void
    {
        PublishToPicker::dispatch($product);
        PublishToCourier::dispatch($product);
    }
}
