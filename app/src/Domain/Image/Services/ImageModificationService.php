<?php

namespace Domain\Image\Services;

use Domain\Image\DTO\ImageMinDTO;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ImageModificationService
{
    public function createCertificateMinImage(ImageMinDTO $imageMinDTO): void
    {
        Image::make(Storage::disk('public')
            ->path($imageMinDTO->getUrl()))
            ->fit($imageMinDTO->getWidth(), $imageMinDTO->getHeight())
            ->save(Storage::disk('public')
                ->path($imageMinDTO->getNewUrl()));
    }
}
