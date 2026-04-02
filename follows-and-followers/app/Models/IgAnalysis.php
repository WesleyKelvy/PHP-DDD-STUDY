<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $sale_id
 * @property string $user_id
 * @property array<array-key, mixed> $non_followers
 * @property int $followers_count
 * @property int $following_count
 * @property int $non_followers_count
 * @property \Illuminate\Support\Carbon $processed_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Sale|null $sale
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IgAnalysis newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IgAnalysis newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IgAnalysis query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IgAnalysis whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IgAnalysis whereFollowersCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IgAnalysis whereFollowingCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IgAnalysis whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IgAnalysis whereNonFollowers($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IgAnalysis whereNonFollowersCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IgAnalysis whereProcessedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IgAnalysis whereSaleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IgAnalysis whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IgAnalysis whereUserId($value)
 * @mixin \Eloquent
 */
class IgAnalysis extends Model
{
    protected $fillable = [
        'sale_id',
        'user_id',
        'non_followers',
        'following_count',
        'followers_count',
        'non_followers_count',
        'processed_at',
    ];

    protected $casts = [
        'non_followers' => 'array',
        'processed_at'  => 'datetime',
    ];

    // Relationships
    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
