<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $name
 * @property string|null $domain
 * @property string|null $path
 * @property int|null $time_zone_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LogEvent> $events
 * @property-read int|null $events_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LogServiceTitanJob> $jobBookings
 * @property-read int|null $job_bookings_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 *
 * @method static \Database\Factories\MarketFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Market newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Market newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Market onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Market query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Market whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Market whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Market whereDomain($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Market whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Market whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Market wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Market whereTimeZoneId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Market whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Market withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Market withoutTrashed()
 *
 * @mixin \Eloquent
 */
class Market extends Model
{
    /** @use HasFactory<\Database\Factories\MarketFactory> */
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'domain',
        'path',
        'time_zone_id',
    ];

    /**
     ********************************************************************
     * Relationships
     ********************************************************************
     */
    public function jobBookings(): HasMany
    {
        return $this->hasMany(LogServiceTitanJob::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(LogEvent::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'market_user', 'market_id', 'user_id');
    }
}
