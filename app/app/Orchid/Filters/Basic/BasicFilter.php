<?php

namespace App\Orchid\Filters\Basic;

use Orchid\Filters\Filter;

abstract class BasicFilter extends Filter
{
    protected ?string $dbColumn = null;

    protected static $delimiter = ', ';

    protected function getDbColumn(): string
    {
        return $this->dbColumn ?? $this->getParam();
    }

    protected function getParam(): string
    {
        return $this->parameters[0];
    }

    protected function getValue(): string|array|null
    {
        $param = $this->getParam();

        return $this->request->get($param);
    }
}
