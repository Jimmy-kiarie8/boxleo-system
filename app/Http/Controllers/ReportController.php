<?php

namespace App\Http\Controllers;

use App\Exports\ReportExport;
use App\Exports\AnalysisExport;
use App\Exports\SaleExport;
use App\Mail\report\ReportMail;
use App\Models\Charge;
use App\Models\Ou;
use App\Models\ProductSale;
use App\Models\Report;
use App\Models\RiderSale;
use App\Models\Sale;
use App\Models\SaleZone;
use App\Models\Shipcharge;
use App\Models\User;
use App\Notifications\ExcelExportCompleteNotification;
use App\Seller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    /*
    public function reports(Request $request)
    {

        // return $request->all();
        $custom_report = new Report();

        $data = $request->validate([
            'report' => 'required',
            'start_date' => 'required',
            'end_date' => 'required'
        ]);
        // return $request->all();
        // return $request->status;

        if ($request->report == 'Custom') {
            $data = $request->validate([
                'custom' => 'required'
            ]);

            return $custom_report->custom_report($request->custom);
        }

        $start_date = Carbon::parse($request->start_date);
        $end_date = Carbon::parse($request->end_date);
        $report = $request->report;
        $sales = Sale::select('id', 'order_no', 'total_price', 'delivery_status', 'status', 'sub_total', 'client_id', 'created_at', 'mpesa_code', 'delivery_date', 'delivered_on', 'dispatched_on', 'returned_on', 'customer_notes')->setEagerLoads([])->with(['products' => function($q) {
            $q->setEagerLoads([]);
        }, 'client' => function($q) {
            $q->select('id', 'name', 'phone', 'address');
        }]);
        if ($report == 'Status') {
            if (!empty($request->delivery_status)) {
                $sales = $sales->whereIn('delivery_status', $request->delivery_status);
            }
        } elseif ($report == 'Delivered') {
            $sales = $sales->where('delivery_status', 'Delivered');
            if ($request->delivery_start_date && $request->delivery_end_date) {
                $delivery_start_date = Carbon::parse($request->delivery_start_date);
                $delivery_end_date = Carbon::parse($request->delivery_end_date);
                $sales = $sales->whereBetween('delivered_on', [$delivery_start_date, $delivery_end_date]);
            }
        } elseif ($report == 'Returned') {
            $return_start_date = Carbon::parse($request->return_start_date);
            $return_end_date = Carbon::parse($request->return_end_date);
            if ($return_start_date && $return_end_date) {
                $sales = $sales->where('delivery_status', 'Returned')->whereBetween('returned_on', [$return_start_date, $return_end_date]);
            }
        } elseif ($report == 'Dispatched') {
            $start_date = Carbon::parse($request->start_date);
            $end_date = Carbon::parse($request->end_date);
            if ($start_date && $end_date) {
                $sales = $sales->where('delivery_status', 'Dispatched')->whereBetween('dispatched_on', [$start_date, $end_date]);
            }
        } 
        // elseif ($report == 'Reshipped') {
        //     $return_start_date = Carbon::parse($request->return_start_date);
        //     $return_end_date = Carbon::parse($request->return_end_date);
        //     if ($return_start_date && $return_end_date) {
        //         $sales = $sales->where('status', 'Returned')->whereBetween('returned_on', [$return_start_date, $return_end_date]);
        //     }
        // }
        if (!empty($request->client)) {
            $sales = $sales->whereIn('seller_id', $request->client);
        }
        if ($start_date && $end_date && $report != 'Dispatched') {
            $sales = $sales->whereBetween('created_at', [$start_date, Carbon::parse($end_date)->addDay()]);
        }

        if ($report == 'Remittance') {
            $sales = $sales->where('delivery_status', 'Delivered')->orWhere('delivery_status', 'Returned');

            $sales_sum = $sales->sum('total_price');
            // $sales_charge = $sales->sum('shipping_charges');
        }

        if ($request->product) {
            $pro_sale = new ProductSale();
            $sale_arr = $pro_sale->product_sales($request->product);
            $sales = $sales->whereIn('id', $sale_arr);
        }
        // dd($sales_sum, $sales_charge);

        $sales = $sales->get();
        $sales = $custom_report->report_transform($sales);

        return response()->json([
            'sales' => $sales,
            'table_columns' => $this->table_cols()
        ], 200);
    } */

    public function ex_download(Request $request)
    {
        $data = json_decode($request->packages);
        // (new SaleExport($data))->queue('invoicssses.xlsx');

        (new SaleExport($data))->store(env('APP_NAME') . '/sales/' . Carbon::now() . '.xlsx', 'tenant');

        // string $filePath = null, string $disk = null, string $writerType = null, $diskOptions = []


        // return Excel::download(new SaleExport($data), 'invoices.xlsx');
        // (new SaleExport($data))->queue(Carbon::now() . '-report.xlsx');

        // (new SaleExport($data))->queue(Carbon::now() . '.xlsx');
    }

    public function reports(Request $request)
    {
        $report = new Report();
        if ($request->report == 'Remittance') {

            $sales = $report->repor_filter($request);
            $sales = $sales->orderBy('delivery_status')->paginate(1000);

            $sales = $report->report_transform($sales);
            return response()->json([
                'counts' => [],
                'sales' => $sales,
                'table_columns' => $this->table_cols()
            ], 200);
        } elseif ($request->report == 'Delivered') {
            $request->validate([
                'delivered_on' => 'required'
            ]);
        } elseif ($request->report == 'Returned') {
            $request->validate([
                'returned_on' => 'required'
            ]);
        }


        $counts = $report->repor_filter($request)->select('delivery_status', DB::raw('COUNT(*) as count'))
            ->groupBy('delivery_status')
            ->pluck('count', 'delivery_status');

        // DB::enableQueryLog();

        $sales = $report->repor_filter($request);
        $sales = $sales->paginate(1000);
        // Log::emergency(DB::getQueryLog());
        // Log::alert(DB::getQueryLog());
        $sales = $report->report_transform($sales);
        return response()->json([
            'counts' => $counts,
            'sales' => $sales,
            'table_columns' => $this->table_cols()
        ], 200);
    }

    public function report_download(Request $request)
    {
        // return $request->all();
        try {
            $data = json_decode($request->packages);
            // (new ReportExport($data))->store(env('APP_NAME') . ' ' . Carbon::now()->format('Y-m-d') . ' Report' .'.xlsx');
            $client_name = '';
            $client = null;

            $user = new User();
            $user = $user->logged_user();

            $data->ou_id = ($request->ou_id) ? $request->ou_id : $user->ou_id;

            if ($user->is_vendor) {
                $data->client = [$user->id];
                $client = $user;
                $client_name = $user->name;
            } else {

                if (!empty($data->client)) {
                    if (count($data->client) > 0) {
                        $client = Seller::whereIn('id', $data->client)->first();
                        $client_name = $client->name;
                    } else {
                        $clients = Seller::whereIn('id', $data->client)->first();


                        foreach ($clients as $client) {
                            $client_name = $client->name;
                        }
                    }
                }
            }

            $client_name = ($client) ? $client->name : '';
            $file_name = str_random(6) . env('APP_NAME') . 'courier-' . $client_name . ' ' . Carbon::now()->format('Y-m-d') . '.xlsx';
            // $file_name = env('APP_NAME') . ' ' . $client_name . ' ' . Carbon::now()->format('Y-m-d') . ' Report' . '.xlsx';
            $ou = (Auth::check()) ? Ou::find(Auth::user()->ou_id) : Ou::first();
            // return $data->report;


            // $data = collect($data)->toArray();
            // return $this->query($data);


            // return (new ReportExport($data, $client_name, $ou))->download(env('APP_NAME') . ' ' . Carbon::now()->format('Y-m-d') . ' Report' .'.xlsx');
            (new ReportExport($data, $client_name, $ou))->queue($file_name, 'public')->chain([
                $user->notify(new ExcelExportCompleteNotification(env('APP_URL') . '/app/public/' . $file_name))
            ]);

            // (new ReportExport($data, $client_name, $ou))->queue($file_name, 'spaces')->chain([
            //     $user->notify(new ExcelExportCompleteNotification(Storage::disk('spaces')->url("Reports/$file_name")))
            // ]);

            return 'Report is being Generated. You will receive an email with the file link';
            // return (new ReportExport($data))->download(env('APP_NAME') . ' ' . Carbon::now()->format('Y-m-d') . ' Report' .'.xlsx');

        } catch (\Throwable $th) {
            throw $th;
        }
    }


    public function query($data)
    {
        $report = new Report();
        // $data = collect($this->data)->toArray();
        if ($data['report'] == 'Remittance') {
            $sales = $report->repor_filter(new Request($data))->orderBy('delivery_status')->get();
            return $report->report_transform($sales);
        } else {

            $sales = $report->repor_filter(new Request($data))->get();

            return $report->report_transform($sales);
        }
    }


    public function count_data(Request $request)
    {
        // return;
        $start_date = Carbon::parse($request->start_date);
        $end_date = Carbon::parse($request->end_date);

        $ou_id = $request->ou_id;
        $client = $request->client;
        $delivery_status = $request->delivery_status;
        $created_at = $request->created_at;
        $delivery_date = $request->delivery_date;
        $delivered_on = $request->delivered_on;
        $returned_on = $request->returned_on;
        $dispatched_on = $request->dispatched_on;
        // $status = (Str::lower($request->status) != 'all') ? $request->status : null;
        $report = $request->report;

        return DB::table('sales')
            ->selectRaw('count(*) as total')
            ->selectRaw('count(case when delivery_status = "Delivered" then 1 end) as delivered')
            ->selectRaw('count(case when delivery_status = "Returned" then 1 end) as returns')
            ->selectRaw('count(case when delivery_status = "Dispatched" then 1 end) as dispatched')
            ->selectRaw('count(case when delivery_status = "Scheduled" then 1 end) as scheduled')
            ->when(!empty($client), function ($q) use ($client) {
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
            })
            ->first();
    }


    public function index()
    {
        return Report::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request->all();
        $columns = [];
        $labels = [];
        $order = [];

        foreach ($request->table_column as $item) {
            if ($item['checked']) {
                $columns[] = Str::snake($item['field']);
                $labels[] = $item['label'];
            }
            $order[] = Str::snake($item['field']);
        }
        // return $columns;

        $custom = Report::firstOrCreate(
            [
                'name' => $request->name,
                'user_id' => Auth::id()
            ],
            [
                'columns' => $columns,
                'order' => $order,
                'labels' => $labels,
                'user_id' => Auth::id(),
                'conditions' => $request->conditions
            ]
        );

        return $custom;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // return $request->all();
        $columns = [];
        $labels = [];
        $order = [];

        foreach ($request->table_column as $item) {
            if ($item['checked']) {
                $columns[] = Str::snake($item['field']);
                $labels[] = $item['label'];
            }
            $order[] = Str::snake($item['field']);
        }
        // return $columns;

        return Report::find($id)->update(
            [
                'name' => $request->name,
                'columns' => $columns,
                'order' => $order,
                'labels' => $labels,
                'user_id' => Auth::id(),
                'conditions' => $request->conditions
            ]
        );
        /* 
        $custom = Report::firstOrCreate(
            [
                'name' => $request->name,

                'columns' => $columns,
                'order' => $order,
                'labels' => $labels,
                'user_id' => Auth::id(),
                'conditions' => $request->conditions
            ]
        );

        return $custom; */
    }



    public function remit(Request $request)
    {

        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $vendor = $request->client;
        $sales = new Report();
        $sales = $sales->remit($start_date, $end_date, $vendor);

        return response()->json([
            'sales' => $sales,
            'table_columns' => $this->table_cols()
        ], 200);
        // return $request->all();

        // return Charge::all();
        $custom_report = new Report();

        // $end_date = Carbon::tomorrow();
        // $start_date = $end_date->subDays(30);
        // $charges = Charge::whereBetween('created_at', [$start_date, $end_date])->get();

        $charges = Shipcharge::where('remmited', false)->whereBetween('created_at', [$start_date, $end_date])->get();
        // $charges = Shipcharge::where('remmited', false)->get();
        // $charges = DB::table('shipcharges')->where('remmited', false)->get('sale_id');
        // $charges = DB::table('shipcharges')->whereBetween('created_at', [$start_date, $end_date])->get();
        $sale_id = [];
        foreach ($charges as $key => $charge) {
            $sale_id[] = $charge->sale_id;
        }

        $sales = Sale::setEagerLoads([])->with(['services', 'sellers'])->whereIn('id', $sale_id);
        // dd($sales);


        $sales = $sales->where('delivery_status', 'Delivered')->orWhere('delivery_status', 'Returned');

        $sales_sum = $sales->sum('total_price');
        // $sales_charge = $sales->sum('shipping_charges');

        // dd($sales_sum, $sales_charge);

        // if (!empty($request->client)) {
        //     $sales = $sales->whereIn('seller_id', $request->client);
        // }
        // if ($start_date && $end_date) {
        //     $sales = $sales->whereBetween('created_at', [$start_date, $end_date]);
        // }

        $sales = $sales->get();
        $sales = $custom_report->report_transform($sales);
    }


    public function table_cols()
    {

        $labels = [
            "Created on",
            "Order No.",
            "Client Name",
            "Client Phone",
            "Client Address",
            "Product Name",
            // "Created By",
            "Delivery Status",
            // "Status",
            "Delivery Date",
            "Dispatched On",
            "Delivered On",
            "Returned On",
            "Cancelled On",
            // "Sub total",
            "Cod amount",
            "Charges",
            "Quantity",
            "M-pesa Code",
            "Notes"
        ];

        $fields = [
            "created_at",
            "order_no",
            "client_name",
            "client_phone",
            "client_address",
            "product_name",
            // "created_by",
            "delivery_status",
            "delivery_date",
            "dispatched_on",
            "delivered_on",
            "returned_on",
            "cancelled_on",
            "total_price",
            // "sub_total",
            // "discount",
            "shipping_charges",
            "quantity",
            "mpesa_code",
            "customer_notes"
        ];
        return $merged = collect($labels)->zip($fields)->transform(function ($values) {
            // var_dump($values[1]);
            return [
                'text' => $values[0],
                'value' => $values[1]
            ];
        });
    }
    
    public function downloadExcel(Request $request)
    {
        $data = $request->input('data');
        $headers = $request->input('headers');
        $averageRates = $request->input('averageRates');

        return Excel::download(new AnalysisExport($data, $headers, $averageRates), 'report.xlsx');
    }
}
