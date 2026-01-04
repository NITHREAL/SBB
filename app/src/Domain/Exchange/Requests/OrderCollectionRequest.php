<?php

namespace Domain\Exchange\Requests;

class OrderCollectionRequest extends CollectionRequest
{
    public function getItemRequestClass(): string
    {
        return OrderItemRequest::class;
    }
}
