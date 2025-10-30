<?php

declare(strict_types=1);

namespace App\Models\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

trait HasMarketScope
{
    protected function applyMarketFilter(Builder $query, array $marketIds = []): Builder
    {
        if (filled($marketIds)) {
            $query->whereIn("{$this->getTable()}.market_id", $marketIds);
        }

        return $query;
    }

    /**
     * Restrict results based on user permissions and requested market IDs.
     */
    public function scopeForUserMarkets(Builder $query, ?User $user, array $requestedMarketIds = []): Builder
    {
        if (! $user) {
            return $query;
        }

        if ($user->isAdmin()) {
            return $this->applyMarketFilter($query, $requestedMarketIds);
        }

        $allowedMarketIds = $user->markets()->pluck('markets.id')->toArray();

        if (filled($requestedMarketIds)) {
            $marketIds = array_values(array_intersect($allowedMarketIds, $requestedMarketIds));
        }

        return $this->applyMarketFilter($query, $marketIds);
    }
}
