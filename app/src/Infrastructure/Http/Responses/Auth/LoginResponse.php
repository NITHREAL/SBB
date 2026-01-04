<?php

namespace Infrastructure\Http\Responses\Auth;

use Illuminate\Http\JsonResponse;
use Infrastructure\Http\Responses\ApiResponse;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class LoginResponse
{
    public static function handle(array $data): JsonResponse
    {
        return ApiResponse::handle(
            $data,
            ResponseAlias::HTTP_OK,
        );
    }
}
