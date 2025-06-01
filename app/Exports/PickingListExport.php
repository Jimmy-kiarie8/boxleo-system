<?php

namespace App\Exports;

use App\Models\Sale;
use App\Scopes\ProductScope;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PickingListExport implements FromCollection, WithHeadings, WithMapping
{
    protected $start_date;
    protected $end_date;

    public function __construct($start_date, $end_date)
    {
        $this->start_date = Carbon::parse($start_date);
        $this->end_date = Carbon::parse($end_date);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Sale::setEagerLoads([])->with(['products' => function ($query) {
            $query->withoutGlobalScope(ProductScope::class)->setEagerLoads([]);
        }, 'client'])
        ->whereBetween('delivery_date', [$this->start_date, $this->end_date])
        ->where('delivery_status', 'Scheduled')
        ->take(300)
        ->get();
    }

    /**
     * Define the headers for the Excel file.
     */
    public function headings(): array
    {
        return [
            'Order ID', 
            'Client Name', 
            'Delivery Date', 
            'Product Names', 
            'Quantity', 
            'Delivery Status'
        ];
    }

    /**
     * Map the data for each row.
     */
    public function map($order): array
    {
        return [
            $order->id,
            $order->client->name,
            $order->delivery_date,
            $order->products->pluck('name')->implode(', '), // Concatenates product names
            $order->products->pluck('pivot.quantity')->implode(', '), // Concatenates product quantities
            $order->delivery_status
        ];
    }
}

