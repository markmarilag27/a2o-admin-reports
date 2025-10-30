<?php

namespace App\Services\Reports;

use App\Models\LogEvent;
use Illuminate\Support\Collection;

class ConversionFunnelReport extends BaseReportService
{
    public function get(array $markets, string $startDate, string $endDate): Collection
    {
        [$start, $end] = $this->dateRange($startDate, $endDate);

        $funnel = LogEvent::query()
            ->forUserMarkets($this->user, $markets)
            ->betweenDates($start, $end)
            ->conversionFunnel()
            ->get();

        return $this->calculateConversionPercentages($funnel);
    }

    protected function calculateConversionPercentages($funnel): Collection
    {
        $results = [];
        $grouped = $funnel->groupBy('market_id');

        foreach ($grouped as $marketId => $steps) {
            $previous = null;

            foreach ($steps as $step) {
                $percentage = $previous
                    ? round(($step->conversions_total / $previous) * 100, 2)
                    : 100;

                $results[] = [
                    'market_id' => $marketId,
                    'event_name' => $step->event_name,
                    'conversions_total' => $step->conversions_total,
                    'conversions_percentage' => $percentage,
                ];

                $previous = $step->conversions_total;
            }
        }

        return collect($results);
    }
}
