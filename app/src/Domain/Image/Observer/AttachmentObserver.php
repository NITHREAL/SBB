<?php

namespace Domain\Image\Observer;

use Domain\Image\Models\Attachment;
use Domain\Image\Services\FillBlurHashService;

class AttachmentObserver
{
    public function __construct(
        private readonly FillBlurHashService $blurHashService,
    ) {
    }

    public function creating(Attachment $attachment): void
    {
        $attachment->blur_hash = $this->blurHashService->getFilledBlurHash($attachment);
    }

    public function saving(Attachment $attachment): void
    {
        $attachment->blur_hash = $this->blurHashService->getFilledBlurHash($attachment);
    }

    public function updating(Attachment $attachment): void
    {
        $attachment->blur_hash = $this->blurHashService->getFilledBlurHash($attachment);
    }
}
