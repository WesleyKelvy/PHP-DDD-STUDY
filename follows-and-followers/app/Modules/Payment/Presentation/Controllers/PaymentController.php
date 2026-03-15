<?php

declare(strict_types=1);

namespace App\Modules\Payment\Presentation\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Modules\Log\Application\DTO\CreateLogDTO;
use App\Modules\Log\Application\UseCases\CreateLogUseCase;
use App\Modules\Payment\Application\DTOs\CreatePixPaymentDTO;
use App\Modules\Payment\Application\UseCases\CreatePaymentUseCase;
use App\Modules\Payment\Presentation\Requests\PaymentRequest;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;

final class PaymentController extends Controller
{
    public function __construct(
        private CreatePaymentUseCase $useCase,
        private CreateLogUseCase $writeLog,
    ) {}

    /**
     * Called when client submits the payment form.
     */
    public function create(PaymentRequest $request): Redirector|RedirectResponse
    {
        try {
            $user = Auth::user();

            $sale = $this->useCase->execute(
                new CreatePixPaymentDTO(
                    userId: $user->id,
                    email: $user->email,
                    firstName: $request->first_name ?? explode(' ', $user->name)[0],
                    lastName: $request->last_name ?? (array_slice(explode(' ', $user->name), -1)[0] ?? null),
                    docType: $request->doc_type,
                    docNumber: $request->doc_number,
                ),
                ipAddress: $request->ip(),
            );

            return redirect()->route('payment.pending', ['sale' => $sale->id]);
        } catch (\Throwable $e) {
            $this->writeLog->execute(new CreateLogDTO(
                action: 'payment.fail.on.create',
                userId: Auth::id(),
                entityType: 'Payment',
                entityId: null,
                userAgent: null,
                ipAddress: $request->ip(),
                payload: ['error' => $e->getMessage()],
            ));

            return back()->withErrors([
                'payment' => 'Erro ao gerar o PIX. Tente novamente.',
            ]);
        }
    }

    // To do: Refactor the 2 bollow endpoints to DDD
    /**
     * Waiting screen: shows QR code
     */
    public function pending(Sale $sale): Factory|View
    {
        abort_if($sale->user_id !== Auth::id(), 403);
        abort_if(! $sale->isPending(), 404);

        return view('payment.pending', [
            'sale'    => $sale,
            'pixData' => $sale->mp_payment_data,
        ]);
    }

    /**
     * Success screen: shown after webhook approves the sale.
     */
    public function success(Sale $sale): Factory|View
    {
        abort_if($sale->user_id !== Auth::id(), 403);
        abort_if(! $sale->isApproved(), 404);

        return view('payment.success', compact('sale'));
    }
}
