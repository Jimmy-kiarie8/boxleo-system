<?php

namespace App\Exports;

use App\Rider;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Facades\Log;

class RiderPaymentSlipExport implements FromCollection, WithHeadings, WithMapping
{
    protected $start_date;
    protected $end_date;

    public function __construct($start_date, $end_date)
    {
        $this->start_date = Carbon::parse($start_date)->startOfDay();
        $this->end_date = Carbon::parse($end_date)->endOfDay();
    }

    /**
     * Return the collection of riders and their deliveries for the week.
     */
    public function collection()
    {
        $riders = Rider::with(['sales' => function ($query) {
            $query->whereBetween('sales.delivered_on', [$this->start_date, $this->end_date])
                  ->where('sales.delivery_status', 'Delivered')
                  ->select('sales.*', 'rider_sales.rider_id');
        }])
        ->withCount(['sales as deliveries_count' => function ($query) {
            $query->whereBetween('sales.delivered_on', [$this->start_date, $this->end_date])
                  ->where('sales.delivery_status', 'Delivered');
        }])
        ->where('zone_id', 13)
        ->having('deliveries_count', '>', 0)
        ->get();

        
        return $riders;
    }

    /**
     * Define the headings for the Excel file.
     */
    public function headings(): array
    {
        return [
            'Rider Name',
            'Number of Deliveries',
            'Total Earnings',
        ];
    }

    /**
     * Map the data for each row.
     */
    public function map($rider): array
    {
        $deliveriesCount = $rider->deliveries_count;
        
        // Assuming you have a rate_per_delivery field in the riders table
        // If not, you'll need to define this rate somewhere
        $ratePerDelivery = $rider->rate_per_delivery ?? 100; // Default to 100 if not set
        
        $totalEarnings = $deliveriesCount * $ratePerDelivery;

        return [
            $rider->name,
            $deliveriesCount,
            $totalEarnings,
        ];
    }
}