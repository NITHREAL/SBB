<?php

namespace App\Http\Controllers\Api\V1\Exchange;

use Domain\Exchange\Requests\ProductCollectionRequest;
use Domain\Exchange\Requests\ProductItemRequest;
use Domain\Exchange\Resources\ResultResource;
use Domain\Product\DTO\Exchange\ProductExchangeDTO;
use Domain\Product\Models\Product;
use Domain\Product\Services\Exchange\ProductExchangeService;
use Illuminate\Support\Facades\Log;

class ProductController extends ExchangeController
{
    public function __construct(
        private readonly ProductExchangeService $productExchangeService,
    ) {
    }

    public function exchangeCollection(ProductCollectionRequest $request): ResultResource
    {
        Log::channel('exchange.products')->info('Processing product collection exchange', $request->all());
        return parent::doExchange($request);
    }

    public function exchange(ProductItemRequest $request): Product
    {
        $data = $request->validated();
        $productDTO = ProductExchangeDTO::make($data);

        Log::channel('exchange.product')->info('Processing product item exchange', $data);

        $product = $this->productExchangeService->exchange($productDTO);

        Log::channel('exchange.product')->info(sprintf(
            "%s %s // %.2f %s",
            $request->method(),
            $_SERVER['REQUEST_URI'],
            microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'],
            json_encode($request->all())
        ));

        return $product;
    }
}
