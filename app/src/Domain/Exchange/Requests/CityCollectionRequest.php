<?php

namespace Domain\Exchange\Requests;

class CityCollectionRequest extends CollectionRequest
{
    public function getItemRequestClass(): string
    {
        return CityItemRequest::class;
    }
}
