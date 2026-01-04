<?php

namespace Domain\Exchange\Requests;

class RegionCollectionRequest extends CollectionRequest
{
    public function getItemRequestClass(): string
    {
        return RegionItemRequest::class;
    }
}
