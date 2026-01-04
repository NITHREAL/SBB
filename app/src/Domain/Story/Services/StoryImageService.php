<?php

namespace Domain\Story\Services;

use Domain\Image\Helpers\ImageConvertHelper;
use Domain\Image\Models\Attachment;
use Domain\Story\Models\Story;
use Domain\Story\Models\StoryPage;

class StoryImageService
{
    public function convertStoryImage(Story $story): void
    {
        /** @var Attachment $image */
        $image = $story->image;

        if ($image) {
            $this->convertImageAndUpdate($image);
        }
    }

    public function convertStoryPageImage(StoryPage $storyPage): void
    {
        $image = Attachment::find($storyPage->image);

        if ($image) {
            $this->convertImageAndUpdate($image);
        }
    }

    private function convertImageAndUpdate(Attachment $image): void
    {
        if (ImageConvertHelper::isNeedToConvert($image)) {
            $newUrl = ImageConvertHelper::convertToWebpAndSave($image->physicalPath());

            $pathData = pathinfo($newUrl);

            $image->update([
                'path'      => sprintf('%s/', $pathData['dirname']),
                'name'      => $pathData['filename'],
                'extension' => $pathData['extension'],
                'mime'      => sprintf('image/%s', $pathData['extension']),
            ]);
        }
    }
}
