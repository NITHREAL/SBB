<?php

namespace Infrastructure\Services\Loyalty\RequestDTO\Manzana\FavoriteCategories;

use Illuminate\Support\Arr;
use Infrastructure\Services\Loyalty\RequestDTO\BaseDTO;

class SetUserFavoriteCategoriesDTO extends BaseDTO
{
    public function __construct(
        private readonly string $sessionId,
        private readonly string $contactId,
        private readonly string $personalCampaignSettingsId,
    ) {
    }

    public static function make(array $data): self
    {
        return new self(
            Arr::get($data, 'sessionId'),
            Arr::get($data, 'contactId'),
            Arr::get($data, 'personalCampaignSettingsId'),
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

    public function getPersonalCampaignSettingsId(): string
    {
        return $this->personalCampaignSettingsId;
    }
}
