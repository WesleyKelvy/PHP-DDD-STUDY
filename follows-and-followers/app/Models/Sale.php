<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property string $uuid
 * @property string $user_id
 * @property numeric $amount
 * @property string $status
 * @property string $mp_payment_id
 * @property array<array-key, mixed>|null $mp_payment_data
 * @property \Illuminate\Support\Carbon $paid_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\IgAnalysis|null $igAnalysis
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereMpPaymentData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereMpPaymentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale wherePaidAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereUuid($value)
 * @mixin \Eloquent
 */
class Sale extends Model
{
    protected $fillable = [
        'user_id',
        'amount',
        'status',
        'mp_payment_id',
        'mp_payment_data',
    ];

    protected $casts = [
        'mp_payment_data'=> 'array',
        'mp_payment_data'=> 'array',
        'paid_at'        => 'datetime',
        'amount_cents'   => 'integer',
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
