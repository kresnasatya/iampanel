<?php

namespace App\Http\Livewire\User;

use Livewire\Component;
use Livewire\WithPagination;
use RistekUSDI\Kisara\User as KisaraUser;
use RistekUSDI\Kisara\Client as KisaraClient;
use RistekUSDI\Kisara\UserClientRole as KisaraUserClientRole;

class AssignRole extends Component
{
    use WithPagination;

    public $user_id;
    public $firstName;
    public $lastName;
    public $user_type;
    public $selectedClient;
    public $available_roles;

    public $perPage = 10;
    public $search = '';

    protected $listeners = ['refreshClientRolesTable'];

    public function mount($user_id)
    {
        $this->user_id = $user_id;
        $user = (new KisaraUser(config('sso')))->findById($user_id);
        $this->firstName = $user['firstName'];
        $this->lastName = $user['lastName'];
        $user_type_id = '0';
        $this->user_type = search_user_type($user_type_id);
    }

    public function refreshClientRolesTable()
    {
        $this->perPage = 10;
        $this->search = '';
    }

    public function updatedSelectedClient()
    {
        // Available client roles
        $this->available_roles = (new KisaraUserClientRole(config('sso')))->getAvailableRoles($this->user_id, $this->selectedClient);
    }

    public function assignRole($role)
    {
        $roles = [
            json_decode($role, true)
        ];

        $response = (new KisaraUserClientRole(config('sso')))->storeAssignedRoles($this->user_id, $this->selectedClient, $roles);

        if ($response['code'] === 204) {
            $this->available_roles = (new KisaraUserClientRole(config('sso')))->getAvailableRoles($this->user_id, $this->selectedClient);
            $this->dispatchBrowserEvent('toast:ok', [
                'message' => "Peran berhasil ditambah"
            ]);
            $this->emit('refreshClientRolesTable');
        } else {
            $this->dispatchBrowserEvent('toast:error', [
                'message' => "Peran gagal ditambah"
            ]);
        }
    }

    public function getAvailableClientRolesProperty()
    {
        $available_roles = (new KisaraUserClientRole(config('sso')))->getAvailableRoles($this->user_id, $this->selectedClient);
        return collect($available_roles)->filter(function ($item) {
            $search = strtolower($this->search);
            return preg_match("/{$search}/", strtolower($item['name']));
        })->paginate($this->perPage);
    }

    public function render()
    {
        $raw_clients = (new KisaraClient(config('sso')))->get();
        $hidden_clients = explode(',', config('keycloak-default-clients.clients'));
        $clients = [];
        foreach ($raw_clients as $client) {
            if (!in_array($client['clientId'], $hidden_clients)) {
                array_push($clients, $client);
            }
        }

        return view('livewire.user.assign-role', [
            'clients' => $clients,
            'available_client_roles' => $this->available_client_roles
        ]);
    }
}
