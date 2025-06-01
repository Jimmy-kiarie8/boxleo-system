<?php

namespace App\Jobs;

use App\Events\OrderUploadEvent;
use App\Http\Controllers\GoogledriveController;
use App\Http\Controllers\OrderUploadController;
use App\Http\Controllers\StatusController;
use App\Models\Client;
use App\Models\Product;
use App\Models\ProductSale;
use App\Models\Sale;
use App\Models\Sku;
use App\Notifications\OrderUploadNotification;
use App\Models\User;
use App\Service\SheetUpload;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Spatie\RateLimitedMiddleware\RateLimited;

class GoogleUpload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 360;
    protected $id, $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($id, $user)
    {
        $this->id = $id;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $command = 'upload:google';
        Artisan::call($command, [
            'id' => $this->id, '--user' =>  $this->user
        ]);

        // $service = new SheetUpload;
        // $service->upload($this->user, $this->id);
    }


    public function middleware()
    {
        $rateLimitedMiddleware = (new RateLimited())
            ->allow(10) // The number of jobs to allow
            ->everySeconds(60) // Time span for the rate limit
            ->releaseAfterSeconds(30); // Time to wait if rate limit is exceeded

        return [$rateLimitedMiddleware];
    }

}
