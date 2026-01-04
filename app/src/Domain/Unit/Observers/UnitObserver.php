<?php

namespace Domain\Unit\Observers;

use Domain\Unit\Jobs\Courier\PublishUnitToAmqp as PublishUnitToCourier;
use Domain\Unit\Jobs\Picker\PublishUnitToAmqp as PublishUnitToPicker;
use Domain\Unit\Models\Unit;

class UnitObserver
{
    public function saved(Unit $unit): void
    {
        PublishUnitToPicker::dispatch($unit);
        PublishUnitToCourier::dispatch($unit);
    }
}
