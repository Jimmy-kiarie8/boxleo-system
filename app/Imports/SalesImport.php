<?php

namespace App\Imports;

use App\Models\Sale;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Auth;
// use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
// use App\Models\AutoGenerate;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class SalesImport implements ToModel, WithHeadingRow, WithChunkReading, ShouldQueue, WithBatchInserts, WithMultipleSheets
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Sale([
            //
        ]);
    }
    public function chunkSize(): int
    {
        return 1000;
    }


    public function batchSize(): int
    {
        return 100;
    }


    public function sheets(): array
    {
        return [
            'Orders' => new OrderSheetImport(),
            'Products' => new ProductSheetImport(),
        ];
    }
    public function onUnknownSheet($sheetName)
    {
        // E.g. you can log that a sheet was not found.
        info("Sheet {$sheetName} was skipped");
    }
}
