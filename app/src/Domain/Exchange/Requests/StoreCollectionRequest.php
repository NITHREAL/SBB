<?php

namespace Domain\Exchange\Requests;

class StoreCollectionRequest extends CollectionRequest
{
    public function getItemRequestClass(): string
    {
        return StoreItemRequest::class;
    }
}
