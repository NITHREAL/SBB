<?php

namespace Domain\Exchange\Requests;

class OrderSyncCollectionRequest extends CollectionRequest
{
    public function getItemRequestClass(): string
    {
        return OrderSyncItemRequest::class;
    }
}
