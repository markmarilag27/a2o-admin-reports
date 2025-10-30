<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Traits\HasMarketScope;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

/**
 * @property int $id
 * @property int $market_id
 * @property int $event_name_id
 * @property string $session_id
 * @property array<array-key, mixed>|null $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\EventName $eventName
 * @property-read \App\Models\Market $market
 *
 * @method static Builder<static>|LogEvent betweenDates(string $startDate, string $endDate)
 * @method static Builder<static>|LogEvent conversionFunnel()
 * @method static \Database\Factories\LogEventFactory factory($count = null, $state = [])
 * @method static Builder<static>|LogEvent forUserMarkets(?\App\Models\User $user, array $requestedMarketIds = [])
 * @method static Builder<static>|LogEvent newModelQuery()
 * @method static Builder<static>|LogEvent newQuery()
 * @method static Builder<static>|LogEvent onlyTrashed()
 * @method static Builder<static>|LogEvent query()
 * @method static Builder<static>|LogEvent whereCreatedAt($value)
 * @method static Builder<static>|LogEvent whereData($value)
 * @method static Builder<static>|LogEvent whereDeletedAt($value)
 * @method static Builder<static>|LogEvent whereEventNameId($value)
 * @method static Builder<static>|LogEvent whereId($value)
 * @method static Builder<static>|LogEvent whereMarketId($value)
 * @method static Builder<static>|LogEvent whereSessionId($value)
 * @method static Builder<static>|LogEvent whereUpdatedAt($value)
 * @method static Builder<static>|LogEvent withTrashed(bool $withTrashed = true)
 * @method static Builder<static>|LogEvent withoutTrashed()
 *
 * @mixin \Eloquent
 */
class LogEvent extends Model
{
    /** @use HasFactory<\Database\Factories\LogEventFactory> */
    use HasFactory, HasMarketScope, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'market_id',
        'event_name_id',
        'session_id',
        'data',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'data' => 'array',
        ];
    }

    /**
     ********************************************************************
     * Relationships
     ********************************************************************
     */
    public function market(): BelongsTo
    {
        return $this->belongsTo(Market::class);
    }

    public function eventName(): BelongsTo
    {
        return $this->belongsTo(EventName::class);
    }

    /**
     ********************************************************************
     * Local scope
     ********************************************************************
     */
    #[Scope]
    protected function betweenDates(Builder $query, string $startDate, string $endDate): Builder
    {
        return $query->whereBetween("{$this->getTable()}.created_at", [$startDate, $endDate]);
    }

    #[Scope]
    protected function conversionFunnel(Builder $query): Builder
    {
        return $query
            ->join('event_names', 'event_names.id', '=', "{$this->getTable()}.event_name_id")
            ->select([
                "{$this->getTable()}.market_id",
                'event_names.id as event_id',
                'event_names.name as event_name',
                DB::raw('COUNT(DISTINCT session_id) as conversions_total'),
            ])
            ->groupBy("{$this->getTable()}.market_id", 'event_names.id', 'event_names.name')
            ->orderBy("{$this->getTable()}.market_id")
            ->orderBy('event_names.id');
    }
}
