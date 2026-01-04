<?php

namespace Domain\Basket\Services\OutputPreparing\Splitter\Components;

interface BasketDeliveryParamsInterface
{
    public function getDeliveryParams(array $basketParams): array;
}
