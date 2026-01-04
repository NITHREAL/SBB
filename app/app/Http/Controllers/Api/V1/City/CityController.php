<?php

namespace App\Http\Controllers\Api\V1\City;

use App\Http\Controllers\Controller;
use Domain\City\Resources\CityResource;
use Domain\City\Services\CityService;
use Illuminate\Http\JsonResponse;
use Infrastructure\Http\Responses\ApiResponse;

class CityController extends Controller
{
    public function index(CityService $cityService): JsonResponse
    {
        return ApiResponse::handle(
            CityResource::collection($cityService->getAllCities()),
        );
    }
}
