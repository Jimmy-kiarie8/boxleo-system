<?php

namespace App\Imports;

use App\Models\Sale;

use Maatwebsite\Excel\Concerns\ToModel;
// use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
// use App\Models\AutoGenerate;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithValidation;

class OrderSheetImport implements ToModel, WithHeadingRow, WithChunkReading, ShouldQueue, WithBatchInserts, SkipsEmptyRows, WithValidation
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

    public function rules(): array
    {
        return [
            // 'delivery_date' => 'required|date_format:YYYY-MM-DD',
        ];
    }

    public function isEmptyWhen(array $row): bool
    {
        return $row['order_id'] === '';
    }
}
