<?php

namespace App\Http\Controllers\Api\V1\Faq;

use App\Http\Controllers\Controller;
use Domain\Faq\Resource\FaqCategoryResource;
use Domain\Faq\Resource\FaqPageResource;
use Domain\Faq\Services\Faq\FaqSelectionService;
use Illuminate\Http\JsonResponse;
use Infrastructure\Http\Responses\ApiResponse;

class FaqController extends Controller
{
    public function index(FaqSelectionService $faqSelectionService): JsonResponse
    {
        return ApiResponse::handle(
            FaqPageResource::make(
                $faqSelectionService->getFaqData()
            ),
        );
    }
}
