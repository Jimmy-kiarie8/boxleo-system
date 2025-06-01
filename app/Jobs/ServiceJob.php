<?php

namespace App\Jobs;

use App\Models\Service;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ServiceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 60;
    public $id, $old_status, $vendor_id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($id, $old_status, $vendor_id)
    {
        $this->id = $id;
        $this->old_status = $old_status;
        $this->vendor_id = $vendor_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $service = new Service();
        $service->charge_service($this->id, $this->old_status, $this->vendor_id);
    }
}
