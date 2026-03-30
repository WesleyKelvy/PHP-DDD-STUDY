<?php

declare(strict_types=1);

namespace App\Modules\Payment\Application\UseCases;

use App\Modules\Payment\Application\DTOs\CreatePaymentMercadoPagoResponseDTO;
use App\Modules\Payment\Application\DTOs\CreatePixPaymentDTO;
use App\Modules\Payment\Domain\Entity\SaleEntity;
use App\Modules\Payment\Domain\Factory\SaleFactory;
use App\Modules\Payment\Domain\Gateway\PaymentGateway;
use App\Modules\Payment\Domain\Repo\SaleRepository;
use App\Modules\Payment\Domain\ValueObject\PixPaymentRequest;
use App\Shared\Application\Events\EventDispatcherInterface;

final class CreatePaymentUseCase
{
    public function __construct(
        private PaymentGateway $paymentGateway,
        private SaleRepository $saleRepository,
        private EventDispatcherInterface $eventDispatcher,
    ) {}

    public function execute(
        CreatePixPaymentDTO $paymentDTO,
        string $ipAddress,
    ): SaleEntity {
        $rawPayment = $this->paymentGateway->createPixPayment(
            new PixPaymentRequest(
                userId: $paymentDTO->userId,
                email: $paymentDTO->email,
                firstName: $paymentDTO->firstName,
                lastName: $paymentDTO->lastName,
                docType: $paymentDTO->docType,
                docNumber: $paymentDTO->docNumber,
                amount: (float) config('mercadopago.price'),
            ),
        );

        // dd($rawPayment);

        $paymentData = CreatePaymentMercadoPagoResponseDTO::fromMercadoPago($rawPayment);
        // dd($paymentData);

        $saleEntity = SaleFactory::createPendingFromPix(
            userId: $paymentDTO->userId,
            amount: config('mercadopago.price'),
            mpPaymentId: $paymentData->id,
            mpPaymentData: $paymentData->pixTransactionData,
        );

        $sale = $this->saleRepository->create($saleEntity);

        $sale->markAsCreated(
            mpPaymentId: $saleEntity->id,
            status: $paymentData->status,
            ipAddress: $ipAddress,
        );

        foreach ($sale->pullDomainEvents() as $event) {
            $this->eventDispatcher->dispatch($event);
        }

        return $sale;
    }
}
