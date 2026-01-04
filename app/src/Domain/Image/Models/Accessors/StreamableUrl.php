<?php

namespace Domain\Image\Models\Accessors;

use Domain\Image\Models\Attachment;
use Illuminate\Support\Facades\Storage;

final class StreamableUrl
{
    private string $cdnUrl;

    public function __construct(
        private readonly Attachment $file,
        private readonly string|null $defaultPath
    ) {
        $this->cdnUrl = config('api.cdn.cdn_url');
    }

    public function __invoke(): string|null
    {
        $disk = Storage::disk($this->file->disk);
        $path = $this->getPhysicalPath($this->file);

        return !is_null($path)
            ? $disk->url($path)
            : $this->defaultPath;
    }

    private function getPhysicalPath(object $image): ?string
    {
        if ($image->disk === 'cdn') {
            $result = $this->getCdnPath($image);
        } else {
            $result = $this->getPathOnServer($image);
        }

        return $result;
    }

    private function getCdnPath(object $image): ?string
    {
        $storage = Storage::disk('s3');
        $newName = pathinfo($image->original_name, PATHINFO_FILENAME) . '.webp';

        return !empty($image->original_name)
            ? sprintf('%s%s', $this->cdnUrl, $storage->path($newName))
            : null;
    }

    private function getPathOnServer(object $image): ?string
    {
        if ($image->path === null || $image->name === null) {
            $result = null;
        } else {
            $result = sprintf('%s%s.%s', $image->path, $image->name, $image->extension);
        }

        return $result;
    }
}
