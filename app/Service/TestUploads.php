<?php

namespace App\Service;

use App\Jobs\OrderUpload;
use App\Seller;
use App\Uploads\OrderTransform;
use Google\Client;
use Google\Service\Sheets as ServiceSheets;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Log;
use Throwable;
use Illuminate\Support\Str;

class TestUploads
{

    private $service;
    // private $spreadsheetId;
    // private $sheetName;

    public function __construct()
    {
        $client = new Client();
        $client->setAuthConfig(env('GOOGLE_SERVICE_ACCOUNT_JSON_LOCATION'));
        $client->addScope(ServiceSheets::SPREADSHEETS);
        $this->service = new ServiceSheets($client);
        // $this->spreadsheetId = '1fN_oalSXn_Om_PJtXla3mD7XeX9bTvE4SSVPEc9qbcA';
        // $this->sheetName = 'NOVEMBER 2023';
    }

    public function getPaginatedData($pageNumber, $pageSize, $spreadsheetId, $sheetName)
    {

        // $pageNumber = 1; // Example page number
        // $pageSize = 100; // Example page size

        // Fetch headers
        $headerResponse = $this->service->spreadsheets_values->get($spreadsheetId, "{$sheetName}!A1:P1");
        $headers = $headerResponse->getValues()[0];

        $headers = array_map(function ($header) {
            return Str::snake(strtolower($header));
        }, $headerResponse->getValues()[0]);
    
        $totalRows = $this->getTotalRows($spreadsheetId, $sheetName);
        $offset = ($pageNumber - 1) * $pageSize;
        $startRow = max(2, $totalRows - $offset + 1); // Start from the second row to skip the header
        $endRow = max(2, $startRow - $pageSize);
    
        // dd($totalRows ,'start: ' . $startRow, 'end: ' . $endRow);

        // 2721 < 2621
        // Make sure the range is valid
        if ($startRow > $endRow) {
            $range = sprintf('%s!A%d:P%d', $sheetName, $endRow, $startRow);
            $response = $this->service->spreadsheets_values->get($spreadsheetId, $range);
            $rows = $response->getValues();
    
            // Combine headers with each row
              $dataWithHeaders = collect($rows)->map(function ($row) use ($headers) {
                return array_combine($headers, array_pad($row, count($headers), null));
            });
    
            // Return the array in the original top-to-bottom order
            return $dataWithHeaders->reverse()->values()->all();
        }
    
        return [];
    }


    public function getTotalRows($spreadsheetId, $sheetName)
    {
        $response = $this->service->spreadsheets_values->get($spreadsheetId, $sheetName);
        $values = $response->getValues();

        return count($values);
    }

    public function dispatchJobs($vendor_id, $sheet, $ou_id, $user)
    {
        // $vendor_id = 95;
        $sync_all = true;
        $vendor = Seller::find($vendor_id);
        $jobs = []; // Initialize an array to hold the jobs
        $platform = 'Google Sheets';
    
        $pageNumber = 1;
        $pageSize = 20;
        // $totalRows = $this->getTotalRows();
        $totalRows = 40; // For testing, this should be dynamic in production
        $totalPages = ceil($totalRows / $pageSize);
    
        while ($pageNumber <= $totalPages) {
            $orders = $this->getPaginatedData($pageNumber, $pageSize, $sheet->post_spreadsheet_id, $sheet->sheet_name);
            // Log::warning($orders);

            $google = new OrderTransform;
            $orders = $google->google_transform($orders, $vendor_id, $ou_id, $sync_all);
            // Log::alert($orders);

            $data = ['orders' => $orders, 'vendor_id' => $vendor_id, 'warehouse_id' => 1, $platform];
            $jobs[] = new OrderUpload($user, $data, $vendor);
            $pageNumber++;
        }

        // return $jobs;
    
        // Create the batch with the jobs
        $batch = Bus::batch($jobs)
            ->then(function (Batch $batch) {
                // This job runs after the batch is complete
            })
            ->catch(function (Batch $batch, Throwable $e) {
                // This job runs if a batch job fails
            })
            ->finally(function (Batch $batch) {
                // This job runs regardless of batch completion or failure
            })
            ->name('Process Orders')
            ->dispatch();
    
        return $batch;
    }
    
}
