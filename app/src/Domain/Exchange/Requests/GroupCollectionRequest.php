<?php

namespace Domain\Exchange\Requests;

class GroupCollectionRequest extends CollectionRequest
{
    public function getItemRequestClass(): string
    {
        return GroupItemRequest::class;
    }
}
