<?php

namespace Domain\Image\Helpers;

use Domain\Image\Enums\AttachmentGroupEnum;
use Domain\Image\Models\Attachment;
use Illuminate\Support\Collection;

class ImagePropertiesHelper
{
    public static function setImageProperties(object $item, Attachment $image): object
    {
        $item->setAttribute(
            'image_original',
            ImageUrlHelper::getUrl($image),
        );

        $item->setAttribute(
            'image_blur_hash',
            $image->blur_hash,
        );

        return $item;
    }

    public static function setPreparedImagesProperty(object $item, Collection $images): object
    {
        $item->preparedImages = $images
            ->map(static function ($item) {
                return [
                    'image'     => $item->group === AttachmentGroupEnum::external()->value
                        ? $item->path
                        : $item->stream,
                    'blurHash'  => $item->blur_hash,
                ];
            });

        return $item;
    }
}
