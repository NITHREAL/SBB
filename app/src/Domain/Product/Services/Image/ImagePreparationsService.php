<?php

namespace Domain\Product\Services\Image;

use Domain\Image\Enums\AttachmentGroupEnum;
use Illuminate\Support\Arr;
use Symfony\Component\Mime\MimeTypes;

class ImagePreparationsService
{
    public array $response;
    private const DEFAULT_MIME_TYPE = 'image/jpeg';

    public function __construct(array $response)
    {
        $this->response = $response;
    }

    public function prepareImagesData(): array
    {
        $preparedProductImages = [];

        foreach ($this->response as $productSystemId => $images) {

            $preparedImages = [];

            foreach ($images as $image) {

                $imageUrl = Arr::get($image, 'url');
                $imageName = $this->getFileNameFromUrl($imageUrl);

                $preparedImages[] = [
                    'name' => $imageName,
                    'mime' => $this->getFileMimeTypeByName($imageName),
                    'original_name' => $imageName,
                    'path' => $imageUrl,
                    'main' => Arr::get($image, 'isMain', false),
                    'group' => AttachmentGroupEnum::external()->value
                ];
            }

            $preparedProductImages[$productSystemId] = $preparedImages;
        }

        return $preparedProductImages;
    }

    private function getFileNameFromUrl(string $url): string
    {
        return basename(parse_url($url, PHP_URL_PATH));
    }

    private function getFileMimeTypeByName(string $fileName): string
    {
        $extension = pathinfo($fileName, PATHINFO_EXTENSION);
        $mimeTypes = MimeTypes::getDefault()->getMimeTypes($extension);

        return Arr::first($mimeTypes) ?? self::DEFAULT_MIME_TYPE;
    }
}
