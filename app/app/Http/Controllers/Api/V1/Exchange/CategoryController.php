<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Exchange;

use Domain\Exchange\Requests\CategoryCollectionRequest;
use Domain\Exchange\Requests\CategoryItemRequest;
use Domain\Exchange\Resources\ResultResource;
use Domain\Product\DTO\Exchange\CategoryExchangeDTO;
use Domain\Product\Jobs\Category\UpdateCategoryChildrenListJob;
use Domain\Product\Services\Category\Exchange\CategoryExchangeService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class CategoryController extends ExchangeController
{
    public function __construct(
        private readonly CategoryExchangeService $categoryExchangeService,
    ) {
    }

    public function exchangeCollection(CategoryCollectionRequest $request): ResultResource
    {
        Log::channel('exchange.category')->info('Processing category collection exchange', $request->all());

        $result = parent::doExchange($request);

        UpdateCategoryChildrenListJob::dispatch()->delay(10);

        return $result;
    }

    public function exchange(CategoryItemRequest $request): Model
    {
        $data = $request->validated();
        $categoryDTO = CategoryExchangeDTO::make($data);

        Log::channel('exchange.category')->info('Processing category item exchange', $data);
        return $this->categoryExchangeService->exchange($categoryDTO);
    }
}
