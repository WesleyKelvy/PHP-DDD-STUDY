<?php

declare(strict_types=1);

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Modules\Payment\Application\UseCases\HandleWebhookUseCase;
use App\Modules\Payment\Infra\Webhooks\MercadoPagoSignatureValidator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class WebhookController extends Controller
{
    public function __construct(
        private HandleWebhookUseCase $useCase,
        private MercadoPagoSignatureValidator $validator,
    ) {}

    public function paymentEvent(Request $request): JsonResponse
    {
        if (! $this->validator->isValid($request)) {
            return response()->json(['error' => 'Invalid signature'], 401);
        }

        $this->useCase->execute(
            type: $request->input('type'),
            paymentId: $request->input('data.id'),
            ipAddress: $request->ip(),
        );

        return response()->json(['status' => 'ok']);
    }
}
