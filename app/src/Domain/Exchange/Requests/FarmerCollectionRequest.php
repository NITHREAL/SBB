<?php

namespace Domain\Exchange\Requests;

class FarmerCollectionRequest extends CollectionRequest
{
    public function getItemRequestClass(): string
    {
        return FarmerItemRequest::class;
    }
}
