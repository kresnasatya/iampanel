<?php

namespace App\Http\Livewire\Group;

use Livewire\Component;
use Livewire\WithPagination;
use RistekUSDI\Kisara\Client as KisaraClient;
use RistekUSDI\Kisara\Group as KisaraGroup;
use RistekUSDI\Kisara\GroupClientRole as KisaraGroupClientRole;

class AssignRole extends Component
{
    use WithPagination;

    public $group_id;
    public $group_name;
    public $selectedClient;
    public $available_roles;

    public $perPage = 10;
    public $search = '';

    protected $listeners = ['refreshClientRolesTable'];

    public function mount($group_id)
    {
        $this->group_id = $group_id;
        $group = (new KisaraGroup(config('sso')))->findById($this->group_id);
        $this->group_name = $group['name'];
    }

    public function refreshClientRolesTable()
    {
        $this->perPage = 10;
        $this->search = '';
    }

    public function updatedSelectedClient()
    {
        // Available client roles
        $this->available_roles = (new KisaraGroupClientRole(config('sso')))->getAvailableRoles($this->group_id, $this->selectedClient);
    }

    public function assignRole($role)
    {
        $roles = [
            json_decode($role, true)
        ];

        $response = (new KisaraGroupClientRole(config('sso')))->storeAssignedRoles($this->group_id, $this->selectedClient, $roles);

        if ($response['code'] === 204) {
            $this->available_roles = (new KisaraGroupClientRole(config('sso')))->getAvailableRoles($this->group_id, $this->selectedClient);
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
        return collect($this->available_roles)->filter(function ($item) {
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

        return view('livewire.group.assign-role', [
            'clients' => $clients,
            'available_client_roles' => $this->available_client_roles
        ]);
    }
}
