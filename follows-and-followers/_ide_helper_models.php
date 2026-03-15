<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property int $sale_id
 * @property int $user_id
 * @property array<array-key, mixed> $non_followers
 * @property int $followers_count
 * @property int $following_count
 * @property int $non_followers_count
 * @property \Illuminate\Support\Carbon $processed_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Sale $sale
 * @property-read \App\Models\User $user
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
	#[\AllowDynamicProperties]
	class IdeHelperIgAnalysis {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $user_id
 * @property string $action
 * @property string|null $entity_type
 * @property int|null $entity_id
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property array<array-key, mixed>|null $payload
 * @property \Illuminate\Support\Carbon $created_at
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Log newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Log newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Log query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Log whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Log whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Log whereEntityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Log whereEntityType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Log whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Log whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Log wherePayload($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Log whereUserAgent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Log whereUserId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperLog {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property numeric $amount
 * @property string $status
 * @property string $mp_payment_id
 * @property array<array-key, mixed>|null $mp_payment_data
 * @property \Illuminate\Support\Carbon $paid_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\IgAnalysis|null $igAnalysis
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereMpPaymentData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereMpPaymentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale wherePaidAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereUserId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperSale {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $role
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\IgAnalysis> $igAnalyses
 * @property-read int|null $ig_analyses_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $logs
 * @property-read int|null $logs_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Sale> $sales
 * @property-read int|null $sales_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperUser {}
}

