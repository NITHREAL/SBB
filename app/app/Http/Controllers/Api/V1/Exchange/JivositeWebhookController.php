<?php

namespace App\Http\Controllers\Api\V1\Exchange;

use App\Http\Controllers\Controller;
use Domain\Exchange\Requests\JivositeWebHookRequest;
use Domain\Support\Enums\SupportMessageAuthorEnum;
use Domain\Support\Models\SupportMessage;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class JivositeWebhookController extends Controller
{
    final public function __invoke(JivositeWebHookRequest $request): JsonResponse
    {
        try {
            $data = $request->json();

            SupportMessage::create([
                'user_id' => $data->get('recipient')['id'],
                'text' => $data->get('message')['text'],
                'stuff_only' => false,
                'author' => SupportMessageAuthorEnum::administrator()->value,
            ]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'error' => [
                    'code' => $e->getCode(),
                    'message' => $e->getMessage()
                ]
            ]);
        }

        return response()->json([
            'result' => 'ok'
        ]);
    }
}
