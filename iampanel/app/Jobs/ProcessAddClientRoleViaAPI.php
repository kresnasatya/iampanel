<?php

namespace App\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use RistekUSDI\Kisara\Client as KisaraClientRole;

class ProcessAddClientRoleViaAPI implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $client_id, $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($client_id, $data)
    {
        $this->client_id = $client_id;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        (new KisaraClientRole(config('sso')))->store($this->client_id, $this->data);
    }
}
