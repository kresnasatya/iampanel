<?php

namespace App\Http\Livewire\Client\Roles\Member\Groups;

use Livewire\Component;
use Livewire\WithPagination;
use RistekUSDI\Kisara\Client as KisaraClient;
use RistekUSDI\Kisara\Group as KisaraGroup;
use RistekUSDI\Kisara\GroupClientRole as KisaraGroupClientRole;
use RistekUSDI\Kisara\Role as KisaraRole;

class Add extends Component
{
    use WithPagination;

    public $first = 0;
    public $perPage = 10;
    public $sortField = 'name';
    public $sortAsc = true;
    public $search = '';
    public $client_id;
    public $clientName;
    public $clientProtocol;
    public $role_id;
    public $roleName;

    protected $listeners = ['refreshGroupsTable', 'addOk', 'addFailed'];

    public function mount($client_id, $role_id)
    {
        $this->client_id = $client_id;
        $client = (new KisaraClient(config('sso')))->findById($client_id);
        $this->clientName = $client['clientId'];
        $this->clientProtocol = $client['protocol'];
        $this->role_id = $role_id;
        $role = (new KisaraRole(config('sso')))->findById($role_id);
        $this->roleName = !empty($role) ? $role['name'] : 'Unknown';
    }

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

    public function refreshGroupsTable()
    {
        $this->first = 0;
        $this->perPage = 10;
        $this->search = '';
        $this->sortField = 'name';
        $this->sortAsc = true;
    }

    public function addGroupToClientRole($group_id, $group_name)
    {
        $available_roles = (new KisaraGroupClientRole(config('sso')))->getAvailableRoles($group_id, $this->client_id);
        
        $selected_role = [];
        foreach ($available_roles as $available_role) {
            if ($available_role['name'] == $this->roleName) {
                $selected_role = $available_role;
            }
        }

        $roles = [$selected_role];
        
        $response = (new KisaraGroupClientRole(config('sso')))->storeAssignedRoles($group_id, $this->client_id, $roles);
        if ($response['code'] === 204) {
            $this->emit('addOk', array(
                'group_name' => $group_name
            ));
        } else {
            $this->emit('addFailed', array(
                'group_name' => $group_name
            ));
        }
    }

    public function addOk($data)
    {
        $this->dispatchBrowserEvent('add:ok', [
            'message' => "{$data['group_name']} berhasil ditambahkan ke peran {$this->roleName}"
        ]);
    }

    public function addFailed($data)
    {
        $this->dispatchBrowserEvent('add:failed', [
            'message' => "{$data['group_name']} gagal ditambahkan ke peran {$this->roleName}"
        ]);
    }

    public function getGroupsProperty()
    {
        $params = [
            'first' => $this->first,
            'max' => $this->perPage,
        ];

        if (!empty($this->search)) {
            $params['search'] = $this->search;
        }

        $raw_groups = (new KisaraGroup(config('sso')))->get($params);

        return collect($raw_groups)->sortBy([
            [$this->sortField, $this->sortAsc ? 'asc': 'desc']
        ]);
    }

    public function render()
    {
        return view('livewire.client.roles.member.groups.add', [
            'groups' => $this->groups
        ]);
    }
}
