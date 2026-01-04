<?php

namespace Domain\Exchange\Requests;

class UnitCollectionRequest extends CollectionRequest
{
    public function getItemRequestClass(): string
    {
        return UnitItemRequest::class;
    }
}
