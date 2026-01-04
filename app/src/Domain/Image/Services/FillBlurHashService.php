<?php

namespace Domain\Image\Services;

use Bepsvpt\Blurhash\Facades\BlurHash;
use Domain\Image\Models\Attachment;
use Illuminate\Support\Facades\Storage;

class FillBlurHashService
{
    public function getFilledBlurHash(Attachment $attachment): ?string
    {
        $result = null;

        if ($this->isMimetypeCorrect($attachment->mime)) {
            $fullPath = Storage::disk($attachment->disk)
                ->path($this->getAttachmentFilePath($attachment));

            if (file_exists($fullPath)) {
                $result = BlurHash::encode($fullPath);
            }
        }

        return $result;
    }

    private function getAttachmentFilePath(Attachment $attachment): string
    {
        return sprintf('%s%s.%s', $attachment->path, $attachment->name, $attachment->extension);
    }

    private function isMimetypeCorrect(string $mimeType): bool
    {
        return str_contains($mimeType, 'image/');
    }
}
