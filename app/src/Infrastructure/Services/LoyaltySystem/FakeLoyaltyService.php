<?php

namespace Infrastructure\Services\LoyaltySystem;

class FakeLoyaltyService implements LoyaltyServiceInterface
{

    public function getBonusAccountBalances(): array
    {
        return $this->mockBonusInfo();
    }

    private function mockBonusInfo(): array
    {
        return [
            [
                'type' => 'ACTIVE',
                'amount' => '1000',
            ],
            [
                'type' => 'BLOCKED',
                'amount' => '0',
            ],
            [
                'type' => 'WRITE_OFF',
                'amount' => '0',
            ],
            [
                'type' => 'NOT_ACTIVE',
                'amount' => '0',
            ],
            [
                'type' => 'REVOKED',
                'amount' => '0',
            ],
        ];
    }
}