<?php

namespace App\Http\Controllers\Api\V1\Lottery;

use App\Http\Controllers\Controller;
use Domain\Lottery\DTO\Catalog\LotteryCatalogFiltersDTO;
use Domain\Lottery\DTO\Catalog\LotteryCatalogParamsDTO;
use Domain\Lottery\DTO\LotteryCollectionDTO;
use Domain\Lottery\DTO\LotteryOneDTO;
use Domain\Lottery\Requests\LotteriesListRequest;
use Domain\Lottery\Requests\LotteriesRequest;
use Domain\Lottery\Requests\LotteryCatalogRequest;
use Domain\Lottery\Requests\LotteryOneRequest;
use Domain\Lottery\Resources\Catalog\LotteryCatalogResource;
use Domain\Lottery\Resources\LotteriesAllResource;
use Domain\Lottery\Resources\LotteryOneResource;
use Domain\Lottery\Resources\LotteryResource;
use Domain\Lottery\Services\Catalog\LotteryCatalogService;
use Domain\Lottery\Services\LotteryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Infrastructure\Http\Responses\ApiResponse;

class LotteryController extends Controller
{
    public function index(
        LotteriesRequest $request,
        LotteryService $lotteryService,
    ): JsonResponse {
        $lotteriesCollectionDTO = LotteryCollectionDTO::make($request->validated());

        $lotteries = $lotteryService->getLotteries($lotteriesCollectionDTO);

        return ApiResponse::handle(
            LotteryResource::collection($lotteries),
        );
    }

    public function show(
        string $slug,
        LotteryOneRequest $request,
        LotteryService $lotteryService,
    ): JsonResponse {
        $data = array_merge(
            $request->validated(),
            ['slug' => $slug]
        );

        $lotteryOneDTO = LotteryOneDTO::make($data);

        $lotteryData = $lotteryService->getOneLottery($lotteryOneDTO);

        return ApiResponse::handle(
            LotteryOneResource::make($lotteryData),
        );
    }

    public function list(
        LotteriesListRequest $request,
        LotteryService $lotteryService,
    ): JsonResponse {
        $limit = Arr::get($request->validated(), 'limit');

        $lotteries = $lotteryService->getLotteriesPaginated($limit);

        return ApiResponse::handle(
            LotteriesAllResource::make($lotteries),
        );
    }

    public function catalog(
        string $slug,
        LotteryCatalogRequest $request,
        LotteryCatalogService $lotteryCatalogService,
    ): JsonResponse {
        $data = $request->validated();

        $paramsDTO = LotteryCatalogParamsDTO::make($data, $slug);
        $filtersDTO = LotteryCatalogFiltersDTO::make($data);

        $lotteryCatalogData = $lotteryCatalogService->getLotteryCatalogData(
            $paramsDTO,
            $filtersDTO,
            json_encode($request->all()),
        );

        return ApiResponse::handle(
            LotteryCatalogResource::make($lotteryCatalogData),
        );
    }
}
