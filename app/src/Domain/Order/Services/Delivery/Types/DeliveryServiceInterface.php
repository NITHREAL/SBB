<?php

namespace Domain\Order\Services\Delivery\Types;

use Domain\Order\DTO\Delivery\DeliveryTypeSetDTO;

interface DeliveryServiceInterface
{
    public function getData(DeliveryTypeSetDTO $data): array;
}
