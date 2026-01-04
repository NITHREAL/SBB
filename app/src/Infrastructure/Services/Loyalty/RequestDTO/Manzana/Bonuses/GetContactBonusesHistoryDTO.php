<?php

namespace Infrastructure\Services\Loyalty\RequestDTO\Manzana\Bonuses;

use Illuminate\Support\Arr;
use Infrastructure\Services\Loyalty\RequestDTO\BaseDTO;

class GetContactBonusesHistoryDTO extends BaseDTO
{
    public function __construct(
        private readonly string $sessionId,
        private readonly string $contactId,
        private readonly string $take,
        private readonly string $skip,
    ) {
    }

    public static function make(array $data): self
    {
        $limit = Arr::get($data, 'limit');
        $skip = self::getSkipCount(
            $limit,
            Arr::get($data, 'page'),
        );

        return new self(
            Arr::get($data, 'sessionId'),
            Arr::get($data, 'contactId'),
            $limit,
            $skip,
        );
    }

    public function getSessionId(): string
    {
        return $this->sessionId;
    }

    public function getContactId(): string
    {
        return $this->contactId;
    }

    public function getTake(): string
    {
        return $this->take;
    }

    public function getSkip(): string
    {
        return $this->skip;
    }

    private static function getSkipCount(int $limit, int $page): int
    {
        return $page > 1
            ? ($page * $limit) - $limit
            : 0;
    }
}
