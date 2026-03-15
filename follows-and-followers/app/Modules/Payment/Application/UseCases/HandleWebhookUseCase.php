<?php

declare(strict_types=1);

namespace App\Modules\Payment\Application\UseCases;

use App\Modules\Payment\Application\DTOs\Response\GetPaymentMercadoPagoResponseDTO;
use App\Modules\Payment\Application\Events\WebhookSaleNotFoundEvent;
use App\Modules\Payment\Domain\Gateway\PaymentGateway;
use App\Modules\Payment\Domain\Repo\SaleRepository;
use App\Shared\Application\Events\EventDispatcherInterface;

final class HandleWebhookUseCase
{
    public function __construct(
        private PaymentGateway $paymentGateway,
        private SaleRepository $saleRepository,
        private EventDispatcherInterface $eventDispatcher,
    ) {}

    public function execute(
        string $type,
        string $paymentId,
        ?string $ipAddress = null,
    ): void {
        if ($type !== 'payment' || ! $paymentId) {
            return;
        }

        $sale = $this->saleRepository->findByPaymentId($paymentId);

        if (! $sale) {
            $this->eventDispatcher->dispatch(
                new WebhookSaleNotFoundEvent($paymentId, $ipAddress),
            );

            return;
        }

        $paymentData = GetPaymentMercadoPagoResponseDTO::fromMercadoPago(
            $this->paymentGateway->fetchPayment((int) $paymentId),
        );

        $status = $paymentData->status;

        // Calls the entity to run the business logic. Logs in events!
        match ($status) {
            'approved'  => $sale->approve(
                mpPaymentId: (string) $paymentData->id,
                ipAddress: $ipAddress,
            ),
            'cancelled',
            'rejected'  => $sale->fail(
                mpPaymentId: (string) $paymentData->id,
                ipAddress: $ipAddress,
            ),
            default     => null,
        };

        // Saves the new state of the entire entity in the database
        $this->saleRepository->update($sale);

        foreach ($sale->pullDomainEvents() as $event) {
            $this->eventDispatcher->dispatch($event);
        }
    }
}
