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
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class ReportExport_1 implements FromQuery, WithMapping, WithHeadings, WithChunkReading, ShouldQueue
{
    use Exportable;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function query()
    {
        $data = collect($this->data)->toArray();
        $report = new Report();
        return $report->repor_filter(new Request($data));
    }

    public function chunkSize(): int
    {
        return 1000; // Adjust the chunk size according to your needs
    }
    /**
     * @var Sale $sale
     */
    public function map($sale): array
    {
        $agent_name = ($sale->agent) ? $sale->agent->name : '';

        $product_name = '';
        $quantity = 0;

        foreach ($sale->products as $key => $product) {
            $quantity += $product->pivot->quantity;
            if ($key == 0) {
                $product_name = $product->product_name;
            } else {
                $product_name = $product_name . ' | ' . $product->product_name;
            }
        }
        if ($this->data->report == 'Status') {

            return [
                $sale->order_no,
                $product_name,
                $sale->client->name,
                $sale->client->address,
                $sale->client->email,
                $sale->client->phone,
                $sale->client->gender,
                $sale->client->country,
                $sale->client->city,
                $sale->latitude,
                $sale->longitude,
                $sale->customer_notes,
                $sale->payment_method,
                $sale->total_price,
                $sale->insurance,
                $sale->delivery_status,
                $sale->return_notes,
                $sale->created_at,
                $sale->updated_at,
                $sale->schedule_date,
                $sale->delivery_date,
                $quantity,
                $sale->total_price,
                $agent_name,
            ];
        } elseif ($this->data->report == 'Remittance') {
            return [
                $sale->order_no,
                $sale->total_price,
                $quantity,
                $product_name,
                $sale->client->name,
                $sale->client->address,
                $sale->client->city,
                $sale->client->phone,
                '',
                $sale->delivery_status,
                $sale->updated_at,
                '',
                '',
                $sale->total_price,
                $sale->total_price,
                $sale->customer_notes
            ];
        } else {

            return [
                $sale->order_no,
                $product_name,
                $sale->client->name,
                $sale->client->address,
                $sale->client->email,
                $sale->client->phone,
                $sale->client->city,
                $sale->customer_notes,
                $sale->payment_method,
                $sale->total_price,
                $sale->delivery_status,
                $sale->updated_at,
                $sale->delivery_date,
            ];
        }
    }
    public function headings(): array
    {
        if ($this->data->report == 'Status') {
            return [
                'Order No',
                'Product Item',
                'Receiver Name',
                'Receiver Address',
                'Receiver Email',
                'Receiver Phone',
                'Receiver Gender',
                'Receiver Country',
                'Receiver Town',
                'Receiver Latitude',
                'Receiver Longitude',
                'Special Instruction',
                'Payment Type',
                'Cash On Delivery',
                'Insurance',
                'Order Status',
                'Custome Reasons',
                'Created At',
                'Status Date',
                'Schedule Date',
                'Delivery Date',
                'Quantity',
                'Amount',
                'Agent',
            ];
        } elseif ($this->data->report == 'Remittance') {
            return [
                'Order No',
                'Total Amount',
                'Quantity',
                'Item Description',
                'Client Name',
                'Location',
                'Town',
                'Phone',
                'Upsell(Yes or No)',
                'Order Status',
                'Status Date',
                'Delivery Fee',
                'Fulfillment Fee',
                'COD',
                'Order Total',
                'Custom Reason'
            ];
        } else {

            return [
                'Order No',
                'Product Item',
                'Receiver Name',
                'Receiver Address',
                'Receiver Email',
                'Receiver Phone',
                'Receiver Town',
                'Special Instruction',
                'Payment Type',
                'Cash On Delivery',
                'Order Status',
                'Status Date',
                'Delivery Date'
            ];
        }
    }
}
