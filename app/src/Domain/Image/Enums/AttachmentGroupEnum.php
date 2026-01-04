<?php

namespace Domain\Image\Enums;

use Spatie\Enum\Enum;

/**
 * @method static self external()
 * @method static self local()
 */
class AttachmentGroupEnum extends Enum
{
    private const EXTERNAL = 'external';
    private const LOCAL = 'local';

    protected static function values(): array
    {
        return [
            'external' => self::EXTERNAL,
            'local' => self::LOCAL,
        ];
    }
}
