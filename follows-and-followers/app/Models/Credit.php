<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property mixed $toal
 * @property-read \App\Models\Sale|null $sale
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Credit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Credit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Credit query()
 * @mixin \Eloquent
 */
class Credit extends Model
{
    use HasUuids; // UUID v7 in Laravel 12

    public $incrementing = false;

    protected $fillable = [
        'id',
        'user_id',
        'sale_id',
        'toal',
        'used',
        'reserved',
    ];

    protected $casts = [
        'toal'    => 'interger',
        'used'    => 'array',
        'reserved'=> 'datetime',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }
}
