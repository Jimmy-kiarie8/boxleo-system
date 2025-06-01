<?php

namespace App\Exports;

use App\Models\Sale;
use App\Services\SaleService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderExport implements WithChunkReading, ShouldQueue, FromView
{
    use Exportable;

    public $data, $ou;

    public function __construct(array $data, $ou)
    {
        $this->data = $data;
        $this->ou = $ou;
    }

    public function query()
    {
        DB::enableQueryLog();
        $report = new SaleService();
        $data = $this->data;

        $sales = $report->sale_filter(new Request($data))->get();

        $sale_trans = new Sale();
        $response = $sale_trans->transform_sales($sales, 'custom', '');
        return $response;
    }

    public function chunkSize(): int
    {
        return 1000; // Adjust the chunk size according to your needs
    }

    public function view(): View
    {
        $sales = $this->query();
        return view('report.sale-table', [
            'orders' => $sales,
            'client_name' => 'Boxleo',
            'ou' => $this->ou,
        ]);
    }
}
