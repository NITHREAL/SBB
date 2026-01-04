<?php

namespace Domain\Image\Helpers;

use Domain\Image\Enums\AttachmentGroupEnum;
use Illuminate\Support\Facades\Storage;

class ImageUrlHelper
{
    private const CDN_URL = 'https://927907.selcdn.ru/catalog/';

    public static function getUrl(object $image, $default = null): ?string
    {
        if ($image->group === AttachmentGroupEnum::external()->value) {
            $result = $image->path ?? $default;
        } else {
            $disk = Storage::disk($image->disk);

            $path = self::getPhysicalPath($image);

            $result = $path ? $disk->url($path) : $default;
        }

        return $result;
    }

    public static function getPhysicalPath(object $image): ?string
    {
        if ($image->disk === 'cdn') {
            $result = self::getCdnPath($image);
        } else {
            $result = self::getPathOnServer($image);
        }

        return $result;
    }

    public static function getPathOnServer(object $image): ?string
    {
        if ($image->path === null || $image->name === null) {
            $result = null;
        } else {
            $result = sprintf('%s%s.%s', $image->path, $image->name, $image->extension);
        }

        return $result;
    }

    private static function getCdnPath(object $image): ?string
    {
        $storage = Storage::disk('s3');
        $newName = pathinfo($image->original_name, PATHINFO_FILENAME) . '.webp';

        return !empty($image->original_name)
            ? sprintf('%s%s', self::CDN_URL, $storage->path($newName))
            : null;
    }
}
