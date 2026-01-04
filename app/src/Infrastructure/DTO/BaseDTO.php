<?php

namespace Infrastructure\DTO;

abstract class BaseDTO
{
    protected const DEFAULT_SORT = 500;

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
