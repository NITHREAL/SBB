<?php

namespace Infrastructure\Services\Loyalty\RequestDTO\Manzana\Auth;

use Illuminate\Support\Arr;
use Infrastructure\Helpers\PhoneFormatterHelper;
use Infrastructure\Services\Loyalty\RequestDTO\BaseDTO;

class RequestSMSAuthDTO extends BaseDTO
{
    public function __construct(
        private readonly string $phone,
    ) {
    }

    public static function make(array $data): self
    {
        $phone = PhoneFormatterHelper::addPrefixToPhone(Arr::get($data, 'phone'));

        return new self(
            $phone,
        );
    }

    public function getPhone(): string
    {
        return $this->phone;
    }
}
