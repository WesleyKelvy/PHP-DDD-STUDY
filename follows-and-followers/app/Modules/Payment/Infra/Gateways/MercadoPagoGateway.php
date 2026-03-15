<?php

declare(strict_types=1);

namespace App\Modules\Payment\Infra\Gateways;

use App\Modules\Payment\Domain\Gateway\PaymentGateway;
use App\Modules\Payment\Domain\ValueObject\PixPaymentRequest;
use Illuminate\Support\Str;
use MercadoPago\Client\Common\RequestOptions;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\MercadoPagoConfig;
use RuntimeException;

final class MercadoPagoGateway implements PaymentGateway
{
    public function __construct()
    {
        MercadoPagoConfig::setAccessToken(
            config('mercadopago.access_token'),
        );
    }

    public function createPixPayment(PixPaymentRequest $request): object
    {
        $client = new PaymentClient;
        $requestOptions = new RequestOptions;

        $requestOptions->setCustomHeaders([
            'X-Idempotency-Key: ' . (string) Str::uuid(),
        ]);

        $createRequest = [
            'transaction_amount' => $request->amount,
            'currency_id'        => 'BRL',
            'payment_method_id'  => 'pix',
            'description'        => 'IG-Unfollow-Análise de seguidores',
            'external_reference' => 'mp.sale-' . $request->userId . '-' . now()->timestamp,
            'notification_url'   => route('mercado-pago.webhook/notifications'),
            'payer'              => [
                'email'          => $request->email,
                'first_name'     => $request->firstName,
                'last_name'      => $request->lastName,
                'identification' => [
                    'type'   => $request->docType,
                    'number' => $request->docNumber,
                ],
            ],
        ];

        $response = $client->create($createRequest, $requestOptions);

        if (! $response->id) {
            throw new RuntimeException('MercadoPago payment creation failed');
        }

        return $response;
    }

    public function fetchPayment(int $paymentId): object
    {
        $response = new PaymentClient()->get($paymentId);

        return $response;
    }
}
