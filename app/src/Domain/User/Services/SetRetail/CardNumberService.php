<?php

namespace Domain\User\Services\SetRetail;

use Illuminate\Support\Facades\DB;

class CardNumberService
{
    private const DEFAULT_CHUNK_LIMIT = 100000;

    public function updateCardNumber(int $chunk = null): int
    {
        $chunk = $chunk ?? self::DEFAULT_CHUNK_LIMIT;

        $sql = sprintf(
            "UPDATE users
            SET set_card_number = CONCAT('5', id + 100000000)
            WHERE set_card_number IS NULL
            LIMIT %d",
            $chunk
        );

        $result = DB::selectOne("SELECT COUNT(*) as cnt FROM users WHERE set_card_number IS NULL;");
        $cnt = (int)($result->cnt / $chunk);

        $result = 0;

        for ($i = 0; $i <= $cnt; $i++) {
            $result += DB::update($sql);
        }

        return $result;
    }
}
