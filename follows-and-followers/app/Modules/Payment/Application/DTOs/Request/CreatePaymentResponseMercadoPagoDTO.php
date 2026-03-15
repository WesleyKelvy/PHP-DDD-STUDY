<?php

declare(strict_types=1);

namespace App\Modules\Payment\Application\DTOs;

use App\Modules\Payment\Application\DTOs\Shared\PayerDTO;

final readonly class CreatePaymentMercadoPagoResponseDTO
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
        public ?PointOfInteractionDTO $pointOfInteraction,
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
            dateApproved: $payment->date_approved ?? null,
            pointOfInteraction: isset($payment->point_of_interaction)
            ? PointOfInteractionDTO::fromMercadoPago($payment->point_of_interaction)
            : null,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id'                   => $this->id,
            'status'               => $this->status,
            'status_detail'        => $this->statusDetail,
            'transaction_amount'   => $this->transactionAmount,
            'currency_id'          => $this->currency,
            'description'          => $this->description,
            'payment_method'       => $this->paymentMethod,
            'payment_type'         => $this->paymentType,
            'external_reference'   => $this->externalReference,
            'payer'                => $this->payer->toArray(),
            'date_created'         => $this->dateCreated,
            'date_approved'        => $this->dateApproved,
            'pointOfInteraction'   => $this->pointOfInteraction?->toArray(),
        ];
    }
}

final readonly class PointOfInteractionDTO
{
    public function __construct(
        public ?string $type,
        public ?ApplicationDataDTO $applicationData,
        public ?MercadoPagoPixTransactionDataDTO $transactionData,
    ) {}

    public static function fromMercadoPago(object $pointOfInteraction): self
    {
        return new self(
            type: $pointOfInteraction->type ?? null,
            applicationData: isset($pointOfInteraction->application_data)
                ? ApplicationDataDTO::fromMercadoPago($pointOfInteraction->application_data)
                : null,
            transactionData: isset($pointOfInteraction->transaction_data)
                ? MercadoPagoPixTransactionDataDTO::fromMercadoPago($pointOfInteraction->transaction_data)
                : null,
        );
    }

    /**
     * @return array<string, string>
     */
    public function toArray(): array
    {
        return [
            'type'           => $this->type,
            'applicationData'=> $this->applicationData?->toArray(),
            'transactionData'=> $this->transactionData?->toArray(),
        ];
    }
}

final readonly class MercadoPagoPixTransactionDataDTO
{
    public function __construct(
        public ?string $qrCodeBase64,
        public ?string $qrCode,
        public ?string $ticketURL,
    ) {}

    public static function fromMercadoPago(object $data): self
    {
        return new self(
            qrCodeBase64: $data->qr_code_base64,
            qrCode: $data->qr_code,
            ticketURL: $data->ticket_url,
        );
    }

    /**
     * @return array<string, string>
     */
    public function toArray(): array
    {
        return [
            'qr_code_base64' => $this->qrCodeBase64,
            'qr_code'        => $this->qrCode,
            'ticket_url'     => $this->ticketURL,
        ];
    }
}

final readonly class ApplicationDataDTO
{
    public function __construct(
        public ?string $name,
        public ?string $version,
    ) {}

    public static function fromMercadoPago(object $data): self
    {
        return new self(
            name: $data->name ?? null,
            version: $data->version ?? null,
        );
    }

    /**
     * @return array<string, string>
     */
    public function toArray(): array
    {
        return [
            'name'   => $this->name,
            'version'=> $this->version,
        ];
    }
}
