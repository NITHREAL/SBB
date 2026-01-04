<?php

namespace Infrastructure\Enum;

use Spatie\Enum\Enum;

/**
 * @method static self prod()
 * @method static self dev()
 * @method static self local()
 */
class EnvironmentType extends Enum
{
    protected static function labels(): array
    {
        return [
            'prod'  => 'production',
            'dev'  => 'dev',
            'local'  => 'local',
        ];
    }
}
