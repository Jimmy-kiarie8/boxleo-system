<?php

namespace App\Http\Controllers\Callcentre;

use App\Http\Controllers\Controller;
use App\Models\Call;
use Aws\S3\S3Client;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AudioController extends Controller
{

    public function proxy($id)
    {
        $call = Call::find($id);

        $targetDomain = env('DO_SPACES_ENDPOINT');
        $targetDomain2 = "https://logixsaas.nyc3.digitaloceanspaces.com";

        // Generate the signed URL
        if ($call->downloaded && (starts_with($call->recordUrl, $targetDomain) || starts_with($call->recordUrl, $targetDomain2))) {
            $signedUrl = $this->getSignedUrl($call->recordUrl);

            // Stream the audio
            $client = new Client();

            return new StreamedResponse(function () use ($client, $signedUrl) {
                $response = $client->get($signedUrl);

                // Stream the response body
                echo $response->getBody();
            }, 200, [
                'Content-Type' => 'audio/mpeg', // or the appropriate audio content type
            ]);
        } else {
            $url = $call->recordingUrl;

            $client = new Client();
            $response = $client->get($url);

            $headers = $response->getHeaders();

            return response($response->getBody(), 200, $headers);
        }
    }

    public function getSignedUrl($filePath)
    {
        // Ensure $filePath is the relative path within the bucket, not a full URL
        // Extract the relative path from a full URL, if needed
        $relativePath = parse_url($filePath, PHP_URL_PATH); // e.g., "/boxleo/audios/audio_656a23d3ef1c4.mp3"
        $relativePath = ltrim($relativePath, '/'); // Remove leading "/"

        // Create an S3 client
        $s3Client = new S3Client([
            'version' => 'latest',
            'region' => env('DO_SPACES_REGION'),
            'endpoint' => env('DO_SPACES_ENDPOINT'),
            'credentials' => [
                'key' => env('DO_SPACES_KEY'),
                'secret' => env('DO_SPACES_SECRET'),
            ],
        ]);

        // Generate a pre-signed URL with an expiration time
        $cmd = $s3Client->getCommand('GetObject', [
            'Bucket' => env('DO_SPACES_BUCKET'),
            'Key' => $relativePath, // Use the relative path, not a full URL
        ]);

        $request = $s3Client->createPresignedRequest($cmd, '+30 minutes'); // e.g., 30-minute validity

        return (string) $request->getUri(); // Return the signed URL
    }
    
    public function downloadAudio()
    {
        // $calls = Call::select('recordingUrl')->where('downloaded', false)->whereDate('created_at', '>', Carbon::today()->subDays(2))->where('recordingUrl', '!=', null)->where('durationInSeconds', '>', 0)->take(3)->get();

        try {

            $url = 'http://197.248.0.197:8080/2afc5306486429e27547b6e5b496cd97.mp3';
            $response = Http::get($url);

            if ($response->successful()) {
                $audioData = $response->body();

                // Generate a unique filename based on timestamp and a random string.
                $audioFileName = 'audio_' . uniqid() . '.mp3';

                // Store the audio file using the Storage facade.
                Storage::put('public/audios/' . $audioFileName, $audioData);

                // Update the database record with the file path.
                return $filePath = 'public/audios/' . $audioFileName;
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
