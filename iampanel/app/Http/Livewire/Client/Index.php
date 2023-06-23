<?php

namespace App\Http\Livewire\Client;

use Livewire\Component;
use Livewire\WithPagination;
use RistekUSDI\Kisara\Client as KisaraClient;
use RistekUSDI\Kisara\ClientSecret as KisaraClientSecret;

class Index extends Component
{
    use WithPagination;
    
    public $first = 0;
    public $perPage = 10;
    public $search = '';
    public $sortField = 'name';
    public $sortAsc = true;
    public $hidden_clients = [
        'account',
        'account-console',
        'admin-cli',
        'broker',
        'realm-management',
        'security-admin-console'
    ];
    protected $listeners = ['refreshClientsTable', 'delete', 'deleteOk', 'deleteError', 'copyEnv'];

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortAsc = !$this->sortAsc;
        } else {
            $this->sortAsc = true;
        }

        $this->sortField = $field;
    }

    public function previousPage()
    {
        $this->first = $this->first - $this->perPage;
    }

    public function nextPage()
    {
        $this->first = $this->first + $this->perPage;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function refreshClientsTable()
    {
        $this->first = 0;
        $this->perPage = 10;
        $this->search = '';
        $this->sortField = 'name';
        $this->sortAsc = true;
    }

    public function deleteConfirm($id, $clientId)
    {
        $this->dispatchBrowserEvent('swal:confirm', [
            'title' => "Peringatan!",
            'message' => "Anda yakin akan menghapus aplikasi {$clientId}?",
            'id' => $id,
            'clientId' => $clientId
        ]);
    }

    public function delete($id, $clientId)
    {
        if ($clientId !== config('sso.client_id')) {
            if (in_array($clientId, explode(',', config('keycloak-default-clients.clients')))) {
                $this->emit('deleteError', array(
                    'clientId' => $clientId
                ));
            } else {
                $response = (new KisaraClient(config('sso')))->delete($id);
                if ($response['code'] === 204) {
                    $this->emit('deleteOk', array(
                        'clientId' => $clientId
                    ));
                } else {
                    $this->emit('deleteError', array(
                        'clientId' => $clientId
                    ));
                }
            }
        } else {
            $this->emit('deleteError', array(
                'clientId' => $clientId
            ));
        }
    }

    public function deleteOk($data)
    {
        $this->dispatchBrowserEvent('swal:ok', [
            'title' => 'Berhasil!',
            'message' => "Aplikasi {$data['clientId']} berhasil dihapus!"
        ]);
    }

    public function deleteError($data)
    {
        $this->dispatchBrowserEvent('swal:error', [
            'title' => 'Gagal!',
            'message' => "Aplikasi {$data['clientId']} gagal dihapus!"
        ]);
    }

    public function copyEnv($id, $clientId)
    {
        $admin_url = config('sso.admin_url');
        $base_url = config('sso.base_url');
        $realm = config('sso.realm');
        $public_key = config('sso.realm_public_key');
        $client_secret = (new KisaraClientSecret(config('sso')))->get($id);
        $keycloak_client_secret = !empty($client_secret) ? "\nKEYCLOAK_CLIENT_SECRET={$client_secret}" : '';
        $connector_host_url = request()->getSchemeAndHttpHost();
        $rbac_connector_env = "";
        if (!empty($client_secret)) {
            $rbac_connector_env = "\n# For RBAC connector \n# RBAC_CONNECTOR_HOST_URL=$connector_host_url";
        }
        
        $text = <<<EOF
        KEYCLOAK_ADMIN_URL=$admin_url
        KEYCLOAK_BASE_URL=$base_url
        KEYCLOAK_REALM=$realm
        KEYCLOAK_REALM_PUBLIC_KEY=$public_key
        KEYCLOAK_CLIENT_ID=$clientId
        EOF
        .$keycloak_client_secret."\n# For non Laravel Framework \n# KEYCLOAK_CALLBACK=http://yourapp.test/sso/callback \n# KEYCLOAK_REDIRECT_URL=/home"
        ."\n"
        .$rbac_connector_env
        ."\n"
        ."\n# For ristekusdi/keycloak-token-laravel \n# KEYCLOAK_ALLOWED_RESOURCES=realm-management \n# KEYCLOAK_LEEWAY=60";

        $this->dispatchBrowserEvent('copy:env', [
            'text' => $text
        ]);
    }

    public function getClientsProperty()
    {
        $params = [
            'first' => $this->first,
            'max' => $this->perPage,
        ];

        if (!empty($this->search)) {
            $params = [
                'clientId' => strtolower($this->search),
                'search' => 'true'
            ];
        }
        
        return collect((new KisaraClient(config('sso')))->get($params));
        // dd($clients);
        // return collect($clients);
    }

    public function render()
    {
        return view('livewire.client.index', ['clients' => $this->clients]);
    }
}
