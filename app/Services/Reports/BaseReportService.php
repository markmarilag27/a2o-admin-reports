<?php

declare(strict_types=1);

namespace App\Services\Reports;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

abstract class BaseReportService
{
    public function __construct(protected User $user) {}

    protected function dateRange(string $startDate, string $endDate): array
    {
        return [
            Carbon::parse($startDate)->startOfDay(),
            Carbon::parse($endDate)->endOfDay(),
        ];
    }

    abstract public function get(array $markets, string $startDate, string $endDate): EloquentCollection|Collection;
}
