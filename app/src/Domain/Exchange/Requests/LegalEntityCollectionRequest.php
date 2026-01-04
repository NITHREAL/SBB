<?php

namespace Domain\Exchange\Requests;

class LegalEntityCollectionRequest extends CollectionRequest
{
    public function getItemRequestClass(): string
    {
        return LegalEntityItemRequest::class;
    }
}
