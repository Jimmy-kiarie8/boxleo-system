<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use App\Services\ProductReportService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    protected $reportService;

    public function __construct(ProductReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function dailyProductStatusReport(Request $request)
    {
        $date = $request->input('date');
        return $report = $this->reportService->getDailyProductStatusReport($date);
    }

    public function productSalesReport(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        return $this->reportService->getProductSalesReport($startDate, $endDate);
    }

    public function monthlyProductMovementReport(Request $request)
    {
        $year = $request->input('year');
        $month = $request->input('month');
        return $report = $this->reportService->getMonthlyProductMovementReport($year, $month);
    }

    public function productAvailabilityReport()
    {
        return $report = $this->reportService->getProductAvailabilityReport();
    }

    public function productPerformanceReport(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        return $report = $this->reportService->getProductPerformanceReport($startDate, $endDate);
    }
}
