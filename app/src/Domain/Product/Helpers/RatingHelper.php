<?php

namespace Domain\Product\Helpers;

class RatingHelper
{
    public static function getRatingFormat(?string $rating): int
    {
        return is_null($rating) ? 0 : floor($rating);
    }
}
