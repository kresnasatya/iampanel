<?php

namespace App\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use RistekUSDI\Kisara\UserClientRole as KisaraUserClientRole;

class ProcessSyncUserClientRoleWithAPI implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user_id, $client_id, $roles;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user_id, $client_id, $roles)
    {
        $this->user_id = $user_id;
        $this->client_id = $client_id;
        $this->roles = $roles;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        (new KisaraUserClientRole(config('sso')))->storeAssignedRoles($this->user_id, $this->client_id, $this->roles);
    }
}
