<?php

namespace Domain\Exchange\Requests;

class CategoryCollectionRequest extends CollectionRequest
{
    public function getItemRequestClass(): string
    {
        return CategoryItemRequest::class;
    }
}
