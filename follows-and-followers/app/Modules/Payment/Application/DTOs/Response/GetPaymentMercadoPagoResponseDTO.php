<?php

declare(strict_types=1);

namespace App\Modules\Payment\Application\DTOs\Response;

use App\Modules\Payment\Application\DTOs\Shared\PayerDTO;

final readonly class GetPaymentMercadoPagoResponseDTO
{
    public function __construct(
        public int $id,
        public string $status,
        public string $statusDetail,
        public float $transactionAmount,
        public string $currency,
        public string $description,
        public string $paymentMethod,
        public string $paymentType,
        public string $externalReference,
        public PayerDTO $payer,
        public string $dateCreated,
        public ?string $dateApproved,
    ) {}

    public static function fromMercadoPago(object $payment): self
    {
        return new self(
            id: $payment->id,
            status: $payment->status,
            statusDetail: $payment->status_detail,
            transactionAmount: (float) $payment->transaction_amount,
            currency: $payment->currency_id,
            description: $payment->description,
            paymentMethod: $payment->payment_method_id,
            paymentType: $payment->payment_type_id,
            externalReference: $payment->external_reference,
            payer: PayerDTO::fromMercadoPago($payment->payer),
            dateCreated: $payment->date_created,
            dateApproved: $payment->date_approved,
        );
    }
}
