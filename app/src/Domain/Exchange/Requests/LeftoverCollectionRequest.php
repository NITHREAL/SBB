<?php

namespace Domain\Exchange\Requests;

class LeftoverCollectionRequest extends CollectionRequest
{
    public function getItemRequestClass(): string
    {
        return LeftoverItemRequest::class;
    }
}
