<?php

namespace Infrastructure\Eloquent\Traits;

trait CustomTableName
{
    private function getColumnStringForCustomTableName(string $column): string
    {
        return sprintf('%s.%s', $this->table, $column);
    }
}
