<?php

namespace App\Http\Controllers\Api\V1\City;

use App\Http\Controllers\Controller;
use Domain\City\Models\Region;
use Domain\City\Resources\RegionResource;
use Domain\City\Services\RegionService;
use Illuminate\Http\JsonResponse;
use Infrastructure\Http\Responses\ApiResponse;

class RegionController extends Controller
{
    public function index(RegionService $service): JsonResponse
    {
        return ApiResponse::handle(
            RegionResource::collection($service->getAllRegions()),
        );
    }

    public function view(Region $region): JsonResponse
    {
        return ApiResponse::handle(
            RegionResource::make($region),
        );
    }
    public function cities(Region $region): JsonResponse
    {
        return ApiResponse::handle(
            RegionResource::make($region->load('cities')),
        );
    }
}
