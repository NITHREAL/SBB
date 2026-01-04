<?php

namespace Domain\User\Enums;

use Spatie\Enum\Enum;

/**
 * @method static self openDoor()
 * @method static self dropKeys()
 * @method static self callNeighbors()
 */
class AddressEntranceVariantsEnum extends Enum
{
    private const OPEN_DOOR = "open_door";
    private const DROP_KEYS = "drop_keys";
    private const CALL_NEIGHBORS = "call_neighbors";

    protected static function labels(): array
    {
        return [
            'openDoor'       => __('user.addresses.entrance_variants.open_door'),
            'dropKeys'       => __('user.addresses.entrance_variants.drop_keys'),
            'callNeighbors'  => __('user.addresses.entrance_variants.call_neighbors'),
        ];
    }

    protected static function values(): array
    {
        return [
           'openDoor'       => self::OPEN_DOOR,
           'dropKeys'       => self::DROP_KEYS,
           'callNeighbors'  => self::CALL_NEIGHBORS,
        ];
    }
}
