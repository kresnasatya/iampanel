<?php

namespace App\Http\Livewire\UserClients;

use Livewire\Component;
use Livewire\WithPagination;
use RistekUSDI\SSO\Laravel\Facades\IMISSUWeb;
use RistekUSDI\SSO\Laravel\Auth\AccessToken;
use RistekUSDI\Kisara\Client as KisaraClient;

class Table extends Component
{
    use WithPagination;

    public $perPage = 10;
    public $search = '';

    protected $listeners = ['refreshUserClientsTable'];

    public function refreshUserClientsTable()
    {
        $this->perPage = 10;
        $this->search = '';
    }

    public function getUserClientsProperty()
    {
        $token = IMISSUWeb::retrieveToken();
        $parse_token = (new AccessToken($token))->parseAccessToken();
        $user_clients = $parse_token['resource_access'];
        unset($user_clients['account']);

        $clients = (new KisaraClient(config('sso')))->get();
        foreach ($clients as $sa_client) {
            foreach ($user_clients as $key_client => $client) {
                if ($sa_client['clientId'] === $key_client) {
                    $user_clients[$key_client]['rootUrl'] = !empty($sa_client['rootUrl']) ? $sa_client['rootUrl'] : null;
                    $user_clients[$key_client]['name'] = !empty($sa_client['name']) ? $sa_client['name'] : $sa_client['clientId'];
                    if (isset($sa_client['description']) && !empty($sa_client['description'])) {
                        $description = explode('#', $sa_client['description']);
                        $user_clients[$key_client]['information'] = $description[0];
                        unset($description[0]);

                        $selected_categories = [];
                        foreach ($description as $value) {
                            array_push($selected_categories, trim("#".$value));
                        }
                        $user_clients[$key_client]['categories'] = $selected_categories;
                    } else {
                        $user_clients[$key_client]['information'] = '';
                        $user_clients[$key_client]['categories'] = [];
                    }
                }

                // Hide realm-management
                if ($key_client === 'realm-management') {
                    unset($user_clients[$key_client]);
                }
            }
        }

        $user_clients = collect($user_clients)->filter(function ($item) {
            $search = strtolower($this->search);
            return preg_match("/{$search}/", strtolower($item['name']));
        })->paginate($this->perPage);
        return $user_clients;
    }

    public function render()
    {
        return view('livewire.user-clients.table', [
            'user_clients' => $this->user_clients,
        ]);
    }
}
