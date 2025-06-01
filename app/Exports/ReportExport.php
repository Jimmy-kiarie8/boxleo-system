<?php

namespace App\Exports;

use App\Models\Company;
use App\Models\Report;
use App\Models\Sale;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReportExport implements WithChunkReading, ShouldQueue, FromView
{
    use Exportable;

    public $data, $client_name, $ou;

    public function __construct($data, $client_name, $ou)
    {
        $this->data = $data;
        $this->ou = $ou;
        $this->client_name = $client_name;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function query()
    {
        $report = new Report();
        $data = collect($this->data)->toArray();
        if ($this->data->report == 'Remittance') {
            $sales = $report->repor_filter(new Request($data))->orderBy('delivery_status')->get();
            return $report->report_transform($sales);
        } else {

            $sales = $report->repor_filter(new Request($data))->get();

            return $report->report_transform($sales);
        }
    }

    public function chunkSize(): int
    {
        return 1000; // Adjust the chunk size according to your needs
    }

    public function view(): View
    {
        if ($this->data->report == 'Remittance') {
            return view('report.remittance', [
                'orders' => $this->query(),
                'client_name' => $this->client_name,
                'ou' => $this->ou,
            ]);
        } elseif ($this->data->report == 'Status') {
            return view('report.merchants', [
                'orders' => $this->query(),
                'client_name' => $this->client_name,
                'ou' => $this->ou,
            ]);
        }  elseif ($this->data->report == 'Zone') {
            return view('report.zone', [
                'orders' => $this->query(),
                'client_name' => $this->client_name,
                'ou' => $this->ou,
            ]);
        } else {
            return view('report.report', [
                'orders' => $this->query(),
                'client_name' => $this->client_name,
                'ou' => $this->ou,
            ]);
        }
    }
}
