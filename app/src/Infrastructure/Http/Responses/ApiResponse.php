<?php

namespace Infrastructure\Http\Responses;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ApiResponse
{
    private static array $headers = [
      'Content-Type' =>'application/vnd.api+json',
    ];

    public static function handle($data, null|int $status = null, array $headers = []): JsonResponse
    {
        return new JsonResponse(
          data: $data,
          status: $status ?? Response::HTTP_OK,
          headers: array_merge(static::$headers, $headers),
        );
    }

    public static function handleNoContent(array $headers = []): JsonResponse
    {
        return new JsonResponse(
            data: [],
            status: Response::HTTP_NO_CONTENT,
            headers: array_merge(static::$headers, $headers),
        );
    }
}
