<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Exchange;

use Domain\City\Models\Region;
use Domain\Exchange\DTO\RegionDTO;
use Domain\Exchange\Requests\RegionCollectionRequest;
use Domain\Exchange\Requests\RegionItemRequest;
use Domain\Exchange\Resources\ResultResource;
use Domain\Exchange\Services\Region\RegionService;
use Illuminate\Support\Facades\Log;

class RegionController extends ExchangeController
{
    public function __construct(
        private readonly RegionService $regionService
    ) {
    }

    /**
     * @param RegionCollectionRequest $request
     * @return ResultResource
     */
    public function exchangeCollection(RegionCollectionRequest $request): ResultResource
    {
        Log::channel('exchange.region')->info('Processing region collection exchange', $request->all());
        return parent::doExchange($request);
    }

    /**
     * @param RegionItemRequest $request
     * @return Region Возвращает модель Region.
     */
    public function exchange(RegionItemRequest $request): Region
    {
        $data = $request->validated();
        $regionDTO = RegionDTO::make($data);

        Log::channel('exchange.region')->info('Processing region item exchange', $data);

        return $this->regionService->exchange($regionDTO);
    }
}
