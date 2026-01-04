<?php

namespace Infrastructure\Setting\Builder;

use Infrastructure\Eloquent\Builder\BaseQueryBuilder;

/**
 *  @method static whereSettingKey(string $key)
 *  @method static whereActive()
 */
class SettingQueryBuilder extends BaseQueryBuilder
{

    public function whereSettingKey(string $key): self
    {
        return $this->where('key', $key);
    }

    public function whereActive(): self
    {
        return $this->where('active', true);
    }
}
