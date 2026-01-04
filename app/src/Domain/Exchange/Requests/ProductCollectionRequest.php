<?php

namespace Domain\Exchange\Requests;

class ProductCollectionRequest extends CollectionRequest
{
    public function getItemRequestClass(): string
    {
        return ProductItemRequest::class;
    }
}
