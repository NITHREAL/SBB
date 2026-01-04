<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Exchange;

use Domain\Exchange\DTO\LeftoverDTO;
use Domain\Exchange\Requests\LeftoverCollectionRequest;
use Domain\Exchange\Requests\LeftoverItemRequest;
use Domain\Exchange\Resources\ResultResource;
use Domain\Store\Services\Exchange\LeftoverExchangeService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class LeftoverController extends ExchangeController
{
    public function __construct(
        private readonly LeftoverExchangeService $leftoverExchangeService
    ) {
    }

    public function exchangeCollection(LeftoverCollectionRequest $request): ResultResource
    {
        Log::channel('exchange.leftover')->info('Processing leftover collection exchange', $request->all());
        return parent::doExchange($request);
    }

    public function exchange(LeftoverItemRequest $request): Model
    {
        $data = $request->validated();
        $leftoverDTO = LeftoverDTO::make($data);

        Log::channel('exchange.leftover')->info('Processing leftover item exchange', $data);
        return $this->leftoverExchangeService->exchange($leftoverDTO);
    }
}
