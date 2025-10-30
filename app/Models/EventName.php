<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $name
 * @property int $display_on_client
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LogEvent> $events
 * @property-read int|null $events_count
 *
 * @method static \Database\Factories\EventNameFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventName newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventName newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventName onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventName query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventName whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventName whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventName whereDisplayOnClient($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventName whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventName whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventName whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventName withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventName withoutTrashed()
 *
 * @mixin \Eloquent
 */
class EventName extends Model
{
    /** @use HasFactory<\Database\Factories\EventNameFactory> */
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'display_on_client',
    ];

    /**
     ********************************************************************
     * Relationships
     ********************************************************************
     */
    public function events(): HasMany
    {
        return $this->hasMany(LogEvent::class);
    }
}
