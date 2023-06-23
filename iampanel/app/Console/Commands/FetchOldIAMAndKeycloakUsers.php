<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FetchOldIAMAndKeycloakUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:iamold-keycloak-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch Old IAM and Keycloak users';

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
        $this->line('Fetch Old IAM and Keycloak Users on progress. Please wait!');

        DB::table('tb_users')->truncate();
        DB::table('user_entity')->truncate();

        // Get data from oldiam db connection and store it into tb_users
        DB::connection('oldiam')->table('tb_users')
        ->select("FILL IN THE BLANK")
        ->orderBy('id')
        ->chunk(1000, function ($users) {
            $users_array = [];
            foreach ($users as $user) {
                $users_array[] = collect($user)->toArray();
            }

            DB::table('tb_users')->insert($users_array);
        });

        DB::connection('keycloak')->table('user_entity')
        ->select('first_name', 'last_name', 'username', 'email')
        ->where('realm_id', config('sso.realm'))
        ->whereRaw("username not like '%service%'")
        ->orderBy('id')
        ->chunk(1000, function ($users) {
            $users_array = [];
            foreach ($users as $user) {
                $users_array[] = collect($user)->toArray();
            }

            DB::table('user_entity')->insert($users_array);
        });

        $this->newLine();

        $this->info('Fetch OLD IAM and Keycloak Users was successful!');
    }
}
