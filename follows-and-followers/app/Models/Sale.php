<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @mixin IdeHelperSale
 */
class Sale extends Model
{
    protected $fillable = [
        'user_id',
        'amount',
        'status',
        'mp_payment_id',
        'mp_payment_data',
        'data',
    ];

    protected $casts = [
        'mp_payment_data' => 'array',
        'paid_at'         => 'datetime',
        'amount'          => 'decimal:2',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function igAnalysis(): HasOne
    {
        return $this->hasOne(IgAnalysis::class);
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function markAsApproved(
        string $MercadoPagoPaymentId,
        array $MercadoPagoData,
    ): void {
        $this->update([
            'status'          => 'approved',
            'mp_payment_id'   => $MercadoPagoPaymentId,
            'mp_payment_data' => $MercadoPagoData,
            'paid_at'         => now(),
        ]);
    }
}
