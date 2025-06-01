<?php

namespace App\Traits;

use Google\Client;
use Google\Service\Sheets;
use Google\Service\Sheets\ValueRange;

trait GoogleSheetAuthentication
{
    private function getClient()
    {
        $client = new Client();
        $client->setApplicationName('Your Application Name');
        $client->setScopes([
            Sheets::SPREADSHEETS
        ]);
        $client->setAccessType('offline');
        $client->setAuthConfig(env('GOOGLE_APPLICATION_CREDENTIALS'));

        // Check if the access token is expired and refresh if necessary
        if ($client->isAccessTokenExpired()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            $accessToken = $client->getAccessToken();
            $client->setAccessToken($accessToken);
        }

        return $client;
    }

    private function getService()
    {
        $client = $this->getClient();
        return new Sheets($client);
    }

    public function getSheetValues($spreadsheetId, $range)
    {
        try {
            $service = $this->getService();
            $response = $service->spreadsheets_values->get($spreadsheetId, $range);
            return $response->getValues();
        } catch (\Exception $e) {
            // Handle error appropriately
            return [];
        }
    }

    public function writeSheetValues($spreadsheetId, $range, $values)
    {
        try {
            $service = $this->getService();
            $body = new ValueRange([
                'values' => $values
            ]);
            $params = [
                'valueInputOption' => 'RAW'
            ];
            $response = $service->spreadsheets_values->update($spreadsheetId, $range, $body, $params);
            return $response->getUpdatedCells();
        } catch (\Exception $e) {
            // Handle error appropriately
            return 0;
        }
    }
}
