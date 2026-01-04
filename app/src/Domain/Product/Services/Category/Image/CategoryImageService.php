<?php

namespace Domain\Product\Services\Category\Image;

use Domain\Image\Helpers\ImageConvertHelper;
use Domain\Image\Models\Attachment;
use Domain\Product\Models\Category;
use Illuminate\Support\Facades\Storage;

class CategoryImageService
{
    private const IMAGE_FORMAT = 'image/webp';

    public function attachImageToCategory(Attachment $attachment, Category $category): void
    {
        if ($attachment->mime != self::IMAGE_FORMAT) {
            $imagePath = $attachment->path . $attachment->name . '.' . $attachment->extension;

            $this->convertImageToWebpFormat($attachment, $imagePath);
        } elseif (!$attachment->main) {
            $attachment->update(['main' => true]);
        }

        $category->attachment()->delete();

        $category->attachment()->sync($attachment);
    }

    public function convertImageToWebpFormat(Attachment $attachment, string $imagePath): void
    {
        $webpImage = ImageConvertHelper::convertToWebpAndSave($imagePath);

        $newPath = pathinfo($webpImage);

        $attachment->update([
            'path'      => sprintf('%s/', $newPath['dirname']),
            'name'      => $newPath['filename'],
            'extension' => $newPath['extension'],
            'mime'      => sprintf('image/%s', $newPath['extension']),
            'main'      => true,
        ]);

        Storage::disk('public')->delete($imagePath);
    }
}
