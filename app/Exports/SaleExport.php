<?php

namespace App\Exports;

use App\Mail\errors\JobErrorMail;
use Throwable;
use App\Models\Report;
use App\Models\RiderSale;
use App\Models\Sale;
use App\Models\SaleZone;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class SaleExport implements FromView, ShouldQueue
{
    use Exportable;

    // public $report, $start_date, $end_date, $custom, $client, $delivery_end_date,$delivery_start_date, $delivery_status, $return_start_date, $return_end_date, $user_name, $rider_id, $finance, $zone_to;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        return view('report.sale-excel', [
            'sales' => $this->myQuery()
        ]);
    }

    public function myQuery()
    {
        // return Sale::query()->whereYear('created_at', 2023);

        $custom_report = new Report();
        $created_at = $this->data->created_at;


        
        $client = $this->data->client;
        $delivery_status =(property_exists($this->data, "delivery_status")) ? $this->data->delivery_status : null;
        $created_at =(property_exists($this->data, "created_at")) ? $this->data->created_at : null;
        $delivery_date =(property_exists($this->data, "delivery_date")) ? $this->data->delivery_date : null;
        $delivered_on =(property_exists($this->data, "delivered_on")) ? $this->data->delivered_on : null;
        $delivered_on =(property_exists($this->data, "delivered_on")) ? $this->data->delivered_on : null;
        $returned_on =(property_exists($this->data, "returned_on")) ? $this->data->returned_on : null;
        $dispatched_on =(property_exists($this->data, "dispatched_on")) ? $this->data->dispatched_on : null;

        if ($this->data->report == 'Custom') {
            return $custom_report->custom_report($this->data->custom, $created_at, $this->data->client);
        }

        $report = $this->data->report;
        $sales = Sale::query()->setEagerLoads([])->with(['products' => function($query) {
            $query->setEagerLoads([]);
        }, 'client' => function($query) {
            $query->setEagerLoads([]);
        }, 'seller' => function($query) {
            $query->setEagerLoads([]);
        }, 'services' => function($query) {
            $query->setEagerLoads([]);
        }])->when(!empty($client), function ($q) use ($client) {
            return $q->whereIn('seller_id', $client);
        })->when(!empty($delivery_status), function ($q) use ($delivery_status) {
            return $q->whereIn('delivery_status', $delivery_status);
        })->when(!empty($created_at), function ($q)  use ($created_at) {
            if ($created_at[0] == $created_at[1]) {
                return $q->whereDate('created_at', $created_at);
            } else {
                return $q->whereBetween('created_at', $created_at);
            }
        })->when(($report == 'Delivered'), function ($q)  use ($delivered_on) {
            if ($delivered_on[0] == $delivered_on[1]) {
                return $q->whereDate('delivered_on', $delivered_on);
            } else {
                return $q->whereBetween('delivered_on', $delivered_on);
            }
        })->when(($report == 'Returned'), function ($q)  use ($returned_on) {
            if ($returned_on[0] == $returned_on[1]) {
                return $q->whereDate('returned_on', $returned_on);
            } else {
                return $q->whereBetween('returned_on', $returned_on);
            }
        })->when(($report == 'Dispatched'), function ($q)  use ($dispatched_on) {
            if ($dispatched_on[0] == $dispatched_on[1]) {
                return $q->whereDate('dispatched_on', $dispatched_on);
            } else {
                return $q->whereBetween('dispatched_on', $dispatched_on);
            }
        });

        // if ($report == 'Remittance') {
        //     $sales = $sales->where('delivery_status', 'Delivered')->orWhere('delivery_status', 'Returned');
        //     $sales_sum = $sales->sum('total_price');
        // }

        $sales = $sales->get();
        $sales = $custom_report->report_transform($sales);

        //  dd($sales);
         return ($sales);
    }

    public function failed(Throwable $exception): void
    {
        // handle failed export
        $message = 'Report could not be generated. Please try again. Error message: ' . $exception->getMessage();
        $subject = 'Order upload failed';
        Mail::to(['jimlaravel@gmail.com'])->send(new JobErrorMail($message, $subject));
    }


}
