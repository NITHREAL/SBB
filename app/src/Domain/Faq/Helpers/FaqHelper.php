<?php

namespace Domain\Faq\Helpers;

use Domain\Faq\Models\Faq;
use Illuminate\Support\Str;

class FaqHelper
{
    public static function generateUniqueSlug(string $slugSource, int $faqId = null, string $slug = null): string
    {
        $slug = $slug ?? Str::slug($slugSource);

        if (Faq::query()->where('id', '!=', $faqId)->where('slug', $slug)->exists()) {
            $slug = sprintf('%s-1', $slug);

            return self::generateUniqueSlug($slugSource, $faqId, $slug);
        }

        return $slug;
    }
}
