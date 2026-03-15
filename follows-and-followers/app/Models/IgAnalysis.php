<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperIgAnalysis
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
