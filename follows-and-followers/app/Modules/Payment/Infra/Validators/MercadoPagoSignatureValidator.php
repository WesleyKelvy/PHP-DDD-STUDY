<?php

declare(strict_types=1);

namespace App\Modules\Payment\Infra\Webhooks;

use Illuminate\Http\Request;

final class MercadoPagoSignatureValidator
{
    public function __construct(private ?string $secret = null)
    {
        $this->secret = $secret ?? config('mercadopago.webhook_secret');
    }

    /**
     * Helper: Validate the x-signature header MP sends on every webhook.
     * Docs: https://www.mercadopago.com.br/developers/pt/docs/your-integrations/notifications/webhooks
     */
    public function isValid(Request $request): bool
    {
        $secret = $this->secret;
        $xSig = $request->header('x-signature');
        $xReqId = $request->header('x-request-id');

        if (! $xSig || ! $secret) {
            return false;
        }

        $parts = [];
        foreach (explode(',', $xSig) as $part) {
            [$k, $v] = explode('=', trim($part), 2);
            $parts[$k] = $v;
        }

        $ts = $parts['ts'] ?? '';
        $v1 = $parts['v1'] ?? '';
        $dataId = $request->input('data.id', '');

        $manifest = "id:{$dataId};request-id:{$xReqId};ts:{$ts};";
        $expected = hash_hmac('sha256', $manifest, $secret);

        return hash_equals($expected, $v1);
    }
}
