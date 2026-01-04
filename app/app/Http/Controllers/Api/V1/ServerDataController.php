<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Infrastructure\Http\Responses\ApiResponse;

class ServerDataController extends Controller
{
    public function servertime(): JsonResponse
    {
        return ApiResponse::handle([
            'unixtime' => time(),
        ]);
    }
}
