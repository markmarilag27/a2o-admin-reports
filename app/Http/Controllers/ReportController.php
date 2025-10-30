<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Reports\ConversionFunnelReportRequest;
use App\Http\Requests\Reports\JobBookingsReportRequest;
use App\Services\Reports\ConversionFunnelReport;
use App\Services\Reports\JobBookingsReport;
use Illuminate\Http\JsonResponse;

class ReportController extends Controller
{
    public function jobBookings(JobBookingsReportRequest $request): JsonResponse
    {
        $filters = $request->filters();
        $reportService = new JobBookingsReport($request->user());

        $data = $reportService->get(
            markets: $filters['markets'],
            startDate: $filters['start_date'],
            endDate: $filters['end_date']
        );

        return response()->json(['data' => $data]);
    }

    public function conversionFunnel(ConversionFunnelReportRequest $request): JsonResponse
    {
        $filters = $request->filters();
        $conversionFunnelReport = new ConversionFunnelReport($request->user());

        $data = $conversionFunnelReport->get(
            markets: $filters['markets'],
            startDate: $filters['start_date'],
            endDate: $filters['end_date']
        );

        return response()->json(['data' => $data]);
    }
}
