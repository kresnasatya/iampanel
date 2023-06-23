<?php

namespace App\Http\Livewire\Anonymous;

use App\Jobs\ProcessFetchUsers;
use App\Jobs\ProcessStoreToTmpKeycloakUsers;
use App\Jobs\ProcessStoreUsersToKeycloakRealm;
use App\Traits\ImportBatch;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class SyncUsers extends Component
{
    use ImportBatch;

    const MAHASISWA = '1';
    const DOSEN = '2';
    const PEGAWAI = '3';
    const UPDATE_PASSWORD = "UPDATE_PASSWORD";

    public $batchId;
    public $batchFinished, $batchCancelled = false;
    public $batchProgress = 0;
    public $loading, $hidden = false;
    protected $listeners = ['stopLoading', 'stopHidden'];

    public function stopLoading()
    {
        $this->loading = false;
    }

    public function stopHidden()
    {
        // Stop batch
        $this->batchId = null;
        $this->batchFinished = false;
        $this->batchCancelled = false;
        $this->batchProgress = 0;
        // Un-hide button
        $this->hidden = false;
    }

    public function fetchOldIAMAndKeycloakUsers()
    {
        DB::table('tb_users')->truncate();
        DB::table('user_entity')->truncate();

        $batch = Bus::batch([])->name('Fetch Old IAM and Keycloak Users')->dispatch();
        // Get data from oldiam db connection and store it into tb_users
        DB::connection('oldiam')->table('tb_users')
        ->select(
            'username', 'password', 'name', 'email', 'id'
        )
        ->orderBy('id')
        ->chunk(1000, function ($users) use ($batch) {
            $users_array = [];
            foreach ($users as $user) {
                $users_array[] = collect($user)->toArray();
            }

            $batch->add(new ProcessFetchUsers('oldiam', $users_array));
        });

        DB::connection('keycloak')->table('user_entity')
        ->select('first_name', 'last_name', 'username', 'email')
        ->where('realm_id', config('sso.realm'))
        ->whereRaw("username not like '%service%'")
        ->orderBy('id')
        ->chunk(1000, function ($users) use ($batch) {
            $users_array = [];
            foreach ($users as $user) {
                $users_array[] = collect($user)->toArray();
            }

            $batch->add(new ProcessFetchUsers('keycloak', $users_array));
        });

        $this->batchId = $batch->id;
        $this->emit('stopLoading');
    }

    public function fetchUsersCandidate()
    {
        DB::table('tmp_keycloak_users')->truncate();

        $users_exists = DB::table('tb_users')
        ->selectRaw("FILL IN THE BLANK")
        ->join('user_entity', 'user_entity.username', '=', 'tb_users.username', 'left')
        ->whereRaw("user_entity.username IS NULL")
        ->orderBy('tb_users.username')
        ->exists();

        if ($users_exists) {
            $batch = Bus::batch([])->name('Fetch Users Candidate')->dispatch();

            DB::table('tb_users')
            ->selectRaw("FILL IN THE BLANK")
            ->join('user_entity', 'user_entity.username', '=', 'tb_users.username', 'left')
            ->whereRaw("user_entity.username IS NULL")
            ->orderBy('tb_users.username')
            ->chunk(1000, function ($users) use ($batch) {
                $users_array = [];

                foreach ($users as $user) {
                    $user_array = collect($user)->toArray();
                    $user_array['userpassword'] = '{MD5}'.base64_encode(hex2bin($user_array['userpassword']));
                    $users_array[] = $user_array;
                }

                $batch->add(new ProcessStoreToTmpKeycloakUsers($users_array));
            });

            $this->batchId = $batch->id;
            $this->emit('stopLoading');
        } else {
            $this->emit('stopLoading');
            $this->emit('stopHidden');
            $this->dispatchBrowserEvent('toast:error', [
                'message' => "Data pengguna tidak ditemukan!"
            ]);
        }
    }

    public function storeUsersCandidate()
    {
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
                    'requiredActions' => [self::UPDATE_PASSWORD],
                ];

                $batch->add([
                    new ProcessStoreUsersToKeycloakRealm($data),
                ]);
            }
        });

        $this->batchId = $batch->id;
        $this->emit('stopLoading');
    }

    public function render()
    {
        return view('livewire.anonymous.sync-users');
    }
}
