<?php

namespace Domain\Image\Helpers;

use Domain\Image\Models\Attachment;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class ImageConvertHelper
{
    public static function convertToWebpAndSave(string $fileUrl): string
    {
        ini_set('memory_limit', '2024M');

        try {
            $disk = Storage::disk('public');

            $fileExt = pathinfo($fileUrl, PATHINFO_EXTENSION);

            $newFileUrl = Str::replace($fileExt, 'webp', $fileUrl);

            if ($file = $disk->get($fileUrl)) {
                $disk->put(
                    $newFileUrl,
                    self::convertToWebp($file)
                );
            }
        } catch (\Exception $e) {
            Log::error('Ошибка при конвертации и сохранении изобажения в webp: ' . $e->getMessage());
        }

        return $newFileUrl ?? $fileUrl;
    }

    public static function isNeedToConvert(object $image): bool
    {
        $result = false;

        if ($image instanceof Attachment && $image->disk === 'public') {
            $disk = Storage::disk('public');

            $fileUrl = $image->physicalPath();
            $fileExt = pathinfo($fileUrl, PATHINFO_EXTENSION);
            $newFileUrl = Str::replace($fileExt, 'webp', $fileUrl);

            // Если такого изображения ещё нет
            $result = !$disk->exists($newFileUrl);
        }

        return $result;
    }

    private static function convertToWebp(string $file): \Intervention\Image\Image
    {
        return Image::make($file)->encode('webp');
    }
}
