<?php

namespace App\Console\Commands;

use App\Jobs\ProcessStoreUsersToKeycloakRealm;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;

class StoreUsersCandidate extends Command
{
    const MAHASISWA = '1';
    const DOSEN = '2';
    const PEGAWAI = '3';
    const UPDATE_PASSWORD = "UPDATE_PASSWORD";

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'store:users-candidate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Store users candidate to Keycloak';

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
        $this->line('Store Users Candidate on progress. Please wait!');

        $batch = Bus::batch([])->name('Store Users Candidate')->dispatch();

        DB::table('tmp_keycloak_users')
        ->select('firstName', 'lastName', 'username', 'userpassword')
        ->orderBy('username')
        ->chunk(1000, function ($users) use ($batch) {
            foreach ($users as $user) {
                $new_user = collect($user)->toArray();

                $attributes = [];

                $data = [
                    'firstName' => $new_user['firstName'],
                    'lastName' => $new_user['lastName'],
                    'username' => $new_user['username'],
                    'enabled' => true,
                    'attributes' => $attributes,
                ];

                $batch->add([
                    new ProcessStoreUsersToKeycloakRealm($data),
                ]);
            }
        });

        $retrieved_batch = Bus::findBatch($batch->id);

        $bar = $this->output->createProgressBar($retrieved_batch->totalJobs);

        $bar->start();

        for ($i = 1; $i < $retrieved_batch->totalJobs; $i++) {
            $bar->advance($retrieved_batch->processedJobs());
        }

        $bar->finish();

        $this->newLine();

        $this->info('Store Users Candidate was successful!');
    }
}
