<?php

namespace App\Jobs;

use App\Models\Call;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AudioJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 180;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $calls = Call::select('id', 'recordingUrl')->where('durationInSeconds', '>', 8)->where('downloaded', false)->whereDate('created_at', '>', Carbon::today()->subDays(2))->where('recordingUrl', '!=', null)->latest()->take(100)->get();
            foreach ($calls as $key => $call) {
                $url = $call->recordingUrl;
                $response = Http::get($url);

                if ($response->successful()) {
                    $audioData = $response->body();

                    // Generate a unique filename based on timestamp and a random string.
                    $audioFileName = 'audio_' . uniqid() . '.mp3';

                    // Store the audio file in Digital Ocean Spaces using the Storage facade.
                    Storage::disk('spaces')->put('boxleo/audios/' . $audioFileName, $audioData);

                    // Update the database record with the file path.
                    $filePath = 'https://' . env('DO_SPACES_BUCKET') . '.' . env('DO_SPACES_DB_ENDPOINT') . '/boxleo/audios/' . $audioFileName;
                    // Call::find($call->id)->update(['recordUrl' => $filePath]);
                    Call::find($call->id)->update(['downloaded' => true, 'recordUrl' => $filePath]);
                } else {
                }
            }
            return;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
