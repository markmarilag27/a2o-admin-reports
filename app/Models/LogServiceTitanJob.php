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
 * @property int|null $service_titan_job_id
 * @property \Illuminate\Support\Carbon|null $start
 * @property \Illuminate\Support\Carbon|null $end
 * @property string|null $job_status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Market $market
 *
 * @method static Builder<static>|LogServiceTitanJob betweenDates(string $startDate, string $endDate)
 * @method static Builder<static>|LogServiceTitanJob bookingsReport()
 * @method static \Database\Factories\LogServiceTitanJobFactory factory($count = null, $state = [])
 * @method static Builder<static>|LogServiceTitanJob forUserMarkets(?\App\Models\User $user, array $requestedMarketIds = [])
 * @method static Builder<static>|LogServiceTitanJob newModelQuery()
 * @method static Builder<static>|LogServiceTitanJob newQuery()
 * @method static Builder<static>|LogServiceTitanJob onlyTrashed()
 * @method static Builder<static>|LogServiceTitanJob query()
 * @method static Builder<static>|LogServiceTitanJob whereCreatedAt($value)
 * @method static Builder<static>|LogServiceTitanJob whereDeletedAt($value)
 * @method static Builder<static>|LogServiceTitanJob whereEnd($value)
 * @method static Builder<static>|LogServiceTitanJob whereId($value)
 * @method static Builder<static>|LogServiceTitanJob whereJobStatus($value)
 * @method static Builder<static>|LogServiceTitanJob whereMarketId($value)
 * @method static Builder<static>|LogServiceTitanJob whereServiceTitanJobId($value)
 * @method static Builder<static>|LogServiceTitanJob whereStart($value)
 * @method static Builder<static>|LogServiceTitanJob whereUpdatedAt($value)
 * @method static Builder<static>|LogServiceTitanJob withTrashed(bool $withTrashed = true)
 * @method static Builder<static>|LogServiceTitanJob withoutTrashed()
 *
 * @mixin \Eloquent
 */
class LogServiceTitanJob extends Model
{
    /** @use HasFactory<\Database\Factories\LogServiceTitanJobFactory> */
    use HasFactory, HasMarketScope, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'market_id',
        'service_titan_job_id',
        'start',
        'end',
        'job_status',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'start' => 'datetime',
            'end' => 'datetime',
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

    /**
     ********************************************************************
     * Local scopes
     ********************************************************************
     */
    #[Scope]
    protected function betweenDates(Builder $query, string $startDate, string $endDate): Builder
    {
        return $query->where(function ($q) use ($startDate, $endDate) {
            $q->where("{$this->getTable()}.start", '<=', $endDate)
                ->where("{$this->getTable()}.end", '>=', $startDate);
        });
    }

    #[Scope]
    protected function bookingsReport(Builder $query): Builder
    {
        return $query
            ->select([
                DB::raw("DATE({$this->getTable()}.created_at) as date"),
                "{$this->getTable()}.market_id",
                'markets.name as market_name',
                DB::raw('COUNT(*) as bookings'),
            ])
            ->join('markets', 'markets.id', '=', "{$this->getTable()}.market_id")
            ->groupBy("{$this->getTable()}.market_id", DB::raw("DATE({$this->getTable()}.created_at)"))
            ->orderBy('date', 'asc');
    }
}
