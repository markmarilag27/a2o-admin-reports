<?php

declare(strict_types=1);

namespace App\Services\Reports;

use App\Models\LogServiceTitanJob;
use Illuminate\Database\Eloquent\Collection;

class JobBookingsReport extends BaseReportService
{
    public function get(array $markets, string $startDate, string $endDate): Collection
    {
        [$start, $end] = $this->dateRange($startDate, $endDate);

        return LogServiceTitanJob::query()
            ->forUserMarkets($this->user, $markets)
            ->betweenDates($start, $end)
            ->bookingsReport()
            ->get();
    }
}
