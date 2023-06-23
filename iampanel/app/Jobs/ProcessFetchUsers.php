<?php

namespace App\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ProcessFetchUsers implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $header, $users;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($header, $users)
    {
        $this->header = $header;
        $this->users = $users;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->header === 'oldiam') {
            DB::table('tb_users')->insert($this->users);
        } else if ($this->header === 'keycloak') {
            DB::table('user_entity')->insert($this->users);
        }
    }
}
