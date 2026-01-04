<?php

namespace Infrastructure\Services\LoyaltySystem;

interface LoyaltyServiceInterface
{
    public function getBonusAccountBalances(): array;
}
