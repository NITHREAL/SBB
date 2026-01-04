<?php

namespace Infrastructure\Eloquent\Builder;

use Illuminate\Database\Eloquent\Builder;

/**
 * @method static leftJoin($table, $first, $operator = null, $second = null)
 */
class BaseQueryBuilder extends Builder
{
    public function leftJoin($table, $first, $operator = null, $second = null): Builder
    {
        if ($this->isTableJoined($table)) {
            return $this;
        }

        return $this->join($table, $first, $operator, $second, 'left');
    }

    private function isTableJoined(string $table): bool
    {
        $joins = $this->getQuery()->joins;

        if (empty($joins)) {
            return false;
        }

        foreach ($joins as $join) {
            if ($join->table === $table) {
                return true;
            }
        }

        return false;
    }
}
