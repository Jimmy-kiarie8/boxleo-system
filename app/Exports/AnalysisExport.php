<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class AnalysisExport implements FromCollection, WithHeadings, WithMapping, WithEvents
{
    protected $data;
    protected $headers;
    protected $averageRates;

    public function __construct($data, $headers, $averageRates)
    {
        $this->data = $data;
        $this->headers = $headers;
        $this->averageRates = $averageRates;
    }

    public function collection()
    {
        return collect($this->data);
    }

    public function headings(): array
    {
        return array_map(function($header) {
            return $header['text'];
        }, $this->headers);
    }

    public function map($row): array
    {
        $name = (array_key_exists('agent_name', $row)) ? $row['agent_name']:(array_key_exists('seller_name', $row) ? $row['seller_name'] : 'boxleo');
        return [
            $name,
            $row['total'],
            $this->formatPercentage($row['sales_count_by_status']['Scheduled']),
            $this->formatPercentage((array_key_exists('Buyout', $row['sales_count_by_status'])) ? $row['sales_count_by_status']['Buyout']: 0),
            $this->formatPercentage($row['sales_count_by_status']['Cancelled']),
            $this->formatPercentage($row['sales_count_by_status']['Pending']),
            $this->formatPercentage($row['sales_count_by_status']['Delivered']),
            $this->formatPercentage($row['sales_count_by_status']['Returned']),
        ];
    }

    private function formatPercentage($value)
    {
        return floatval(rtrim($value, '%')) / 100;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet;
                $lastRow = $sheet->getHighestRow() + 2;

                // Add average rates
                $sheet->setCellValue("A{$lastRow}", "Average Rates");
                $sheet->setCellValue("A" . ($lastRow + 1), "Scheduling Rate");
                $sheet->setCellValue("B" . ($lastRow + 1), $this->averageRates['schedulingRate'] / 100);
                $sheet->setCellValue("A" . ($lastRow + 2), "Delivery Rate");
                $sheet->setCellValue("B" . ($lastRow + 2), $this->averageRates['deliveryRate'] / 100);
                $sheet->setCellValue("A" . ($lastRow + 3), "Return Rate");
                $sheet->setCellValue("B" . ($lastRow + 3), $this->averageRates['returnRate'] / 100);
                $sheet->setCellValue("A" . ($lastRow + 4), "Buyout Rate");

                if($this->averageRates['buyoutRate']) {
                    $sheet->setCellValue("B" . ($lastRow + 4),  $this->averageRates['buyoutRate'] / 100);
                } 
                // else {
                //     $sheet->setCellValue("B" . ($lastRow + 4),  $this->averageRates['buyoutRate'] / 100);
                // }

                // Apply percentage format to rate columns
                $rateColumns = ['C', 'D', 'E', 'F', 'G', 'H'];
                foreach ($rateColumns as $column) {
                    $sheet->getStyle("{$column}2:{$column}{$lastRow}")->getNumberFormat()
                        ->setFormatCode(NumberFormat::FORMAT_PERCENTAGE_00);
                }

                // Apply percentage format to average rates
                $sheet->getStyle("B" . ($lastRow + 1) . ":B" . ($lastRow + 4))->getNumberFormat()
                    ->setFormatCode(NumberFormat::FORMAT_PERCENTAGE_00);

                // Auto-size columns
                foreach (range('A', 'H') as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }
            },
        ];
    }
}