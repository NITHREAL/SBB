<?php

namespace Domain\Farmer\Services;

use Domain\Farmer\Models\Farmer;
use Domain\Image\DTO\ImageMinDTO;
use Domain\Image\Helpers\ImagePropertiesHelper;
use Domain\Image\Helpers\ImageUrlHelper;
use Domain\Image\Models\Attachment;
use Domain\Image\Services\ImageModificationService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class FarmerImagesService
{
    private const IMAGES_GROUP = 'certificates';

    private const WIDTH = 180;

    private const HEIGHT = 240;

    public const MODIFY_POSTFIX = self::WIDTH;

    public function __construct(
        protected readonly ImageModificationService $imageModificationService,
    ) {
    }

    public function setImage(object $farmer, Collection $images): object
    {
        $images = $images->where('owner_id', $farmer->id)->where('group', '<>', self::IMAGES_GROUP);

        if ($images->count()) {
            $farmer = ImagePropertiesHelper::setImageProperties($farmer, $images->first());
        }

        return $farmer;
    }

    public function setCertificatesImages(object $farmer, Collection $images): object
    {
        $certificateImages = $images->where('owner_id', $farmer->id)->where('group', self::IMAGES_GROUP);

        if ($certificateImages->count()) {
            $farmer = $this->setPreparedCertificateImages($farmer, $certificateImages);
        }

        return $farmer;
    }

    public function createMinifyImages(Farmer $farmer): void
    {
        $images = $farmer->certificates;

        $imagesIds = $images->pluck('id');

        if (count($imagesIds)) {
            foreach ($imagesIds as $id) {
                /** @var Attachment $image */
                $image = Attachment::query()->where('id', $id)->first();

                $newImage = $this->checkModifyImage($image);

                if(is_null($newImage)) {
                    $imageMinDTO = $this->getPreparedImageData($image);

                    $this->imageModificationService->createCertificateMinImage($imageMinDTO);
                }
            }
        }
    }

    private function getPreparedImageData(Attachment $image): ImageMinDTO
    {
        $url = ImageUrlHelper::getPathOnServer($image);

        $newUrl = ImageUrlHelper::getPathOnServer(
            $this->getModifyPath($image)
        );

        return ImageMinDTO::make([
            'url' => $url,
            'newUrl' => $newUrl,
            'width' => self::WIDTH,
            'height' => self::HEIGHT,
        ]);
    }

    private function getModifyPath(Attachment $item): Attachment
    {
        $newName = sprintf(
            '%s_%s',
            $item->name,
            self::MODIFY_POSTFIX,
        );

        $item->setAttribute(
            'name',
            $newName,
        );

        return $item;

    }

    private function setPreparedCertificateImages(object $farmer, Collection $images): object
    {
        $farmer->certificates = collect();

        foreach ($images as $item) {
            $farmer->certificates->push([
                'id' => $item->id,
                'imageFull'    => ImageUrlHelper::getUrl($item),
                'imageMin'     => ImageUrlHelper::getUrl(
                    $this->checkModifyImage($item) ?? $item
                ),
                'description' => $item->description,
            ]);
        }

        return $farmer;
    }

    private function checkModifyImage(Attachment $item): ?Attachment
    {
        $itemModify = $this->getModifyPath(clone($item));

        $newUrl = ImageUrlHelper::getPathOnServer($itemModify);

        return Storage::disk('public')->exists($newUrl)
            ? $itemModify
            : null;
    }
}
