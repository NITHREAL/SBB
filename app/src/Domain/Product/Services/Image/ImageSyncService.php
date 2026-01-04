<?php

namespace Domain\Product\Services\Image;

use CurlHandle;
use Exception;

readonly class ImageSyncService
{
    public function __construct(
        private ImageParsingService $imageParsingService,
    ) {
    }

    /**
     * @throws Exception
     */
    public function syncImages(): void
    {
        $response = $this->imageParsingService->sendRequest();

        $imagePreparationsService = new ImagePreparationsService($response);

        $prepareImagesData = $imagePreparationsService->prepareImagesData();

        $imageAttachService = new ImageAttachService($prepareImagesData);

        $imageAttachService->attachImagesToProducts();
    }
}
