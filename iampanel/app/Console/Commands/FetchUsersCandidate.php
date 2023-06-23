<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FetchUsersCandidate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:users-candidate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch users candidate from PREVIOUS IAM and Keycloak';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->line('Fetch Users Candidate on progress. Please wait!');

        DB::table('tmp_keycloak_users')->truncate();

        /**
         * NOTE: imagine this query to compare between your_users_table (eg. tb_users) from your previous IAM or User Management
         * and user_entity_table from Keycloak where user_entity_table.username IS NULL.
         *
         */
        $users_exists = DB::table('tb_users')
        ->selectRaw("FILL IN THE BLANK")
        ->join('user_entity', 'user_entity.username', '=', 'tb_users.username', 'left')
        ->whereRaw("user_entity.username IS NULL")
        ->orderBy('tb_users.username')
        ->exists();

        if ($users_exists) {
            DB::table('tb_users')
            ->selectRaw("FILL IN THE BLANK")
            ->join('user_entity', 'user_entity.username', '=', 'tb_users.username', 'left')
            ->whereRaw("user_entity.username IS NULL")
            ->orderBy('tb_users.username')
            ->chunk(1000, function ($users) {
                $users_array = [];

                foreach ($users as $user) {
                    $user_array = collect($user)->toArray();
                    $users_array[] = $user_array;
                }

                DB::table('tmp_keycloak_users')->insert($users_array);
            });
        }

        $this->newLine();
        $this->info('Fetch Users Candidate was successful!');
    }
}
