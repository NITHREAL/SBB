<?php

namespace Infrastructure\Services\Loyalty\Responses\Manzana\Contact;

use Illuminate\Support\Arr;
use Infrastructure\Services\Loyalty\Responses\Manzana\ManzanaResponseInterface;

readonly class UpdateContactResponse implements ManzanaResponseInterface
{
    public function __construct(
        private string $loyaltyUserid,
    ) {
    }

    public static function make(array $data): self
    {
        return new self(
            Arr::get($data, 'value'),
        );
    }

    public function getLoyaltyUserId(): string
    {
        return $this->loyaltyUserid;
    }
}
