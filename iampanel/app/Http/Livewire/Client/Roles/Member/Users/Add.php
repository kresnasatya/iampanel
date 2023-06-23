<?php

namespace App\Http\Livewire\Client\Roles\Member\Users;

use Livewire\Component;
use Livewire\WithPagination;
use RistekUSDI\Kisara\User as KisaraUser;
use RistekUSDI\Kisara\UserClientRole as KisaraUserClientRole;
use RistekUSDI\Kisara\Client as KisaraClient;
use RistekUSDI\Kisara\Role as KisaraRole;
use App\Queries\SearchUsers;

class Add extends Component
{
    use WithPagination;

    public $client_id;
    public $clientName;
    public $clientProtocol;
    public $role_id;
    public $roleName;
    public $first = 0;
    public $perPage = 10;
    public $sortField = 'firstName';
    public $sortAsc = true;
    public $search = '';
    public $q = '';

    protected $listeners = ['refreshUsersTable', 'addOk', 'addFailed'];

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

    public function refreshUsersTable()
    {
        $this->first = 0;
        $this->perPage = 10;
        $this->search = '';
        $this->q = '';
        $this->sortField = 'firstName';
        $this->sortAsc = true;
    }

    public function addUserToClientRole($user_id, $user_name)
    {
        $available_roles = (new KisaraUserClientRole(config('sso')))->getAvailableRoles($user_id, $this->client_id);

        $selected_role = [];
        foreach ($available_roles as $available_role) {
            if ($available_role['name'] == $this->roleName) {
                $selected_role = $available_role;
            }
        }

        $roles = [$selected_role];
        
        $response = (new KisaraUserClientRole(config('sso')))->storeAssignedRoles($user_id, $this->client_id, $roles);
        if ($response['code'] === 204) {
            $this->emit('addOk', array(
                'user_name' => $user_name
            ));
        } else {
            $this->emit('addFailed', array(
                'user_name' => $user_name
            ));
        }
    }

    public function addOk($data)
    {
        $this->dispatchBrowserEvent('add:ok', [
            'message' => "{$data['user_name']} berhasil ditambahkan ke peran {$this->roleName}"
        ]);
    }

    public function addFailed($data)
    {
        $this->dispatchBrowserEvent('add:error', [
            'message' => "{$data['user_name']} gagal ditambahkan ke peran {$this->roleName}"
        ]);
    }

    public function getUsersProperty()
    {
        $params = [
            'first' => $this->first,
            'max' => $this->perPage,
        ];
        
        if (!empty($this->search) && !empty($this->q)) {
            $users_raw = SearchUsers::get(array_merge($params, [
                'search' => $this->search,
                'q' => q_to_array($this->q),
            ]));
        } else {
            if (!empty($this->search)) {
                $params['search'] = glob_search($this->search);
            }

            if (!empty($this->q)) {
                $params['q'] = $this->q;
            }

            $users_raw = (new KisaraUser(config('sso')))->get($params);
        }
        
        return collect($users_raw)->sortBy([
            [$this->sortField, $this->sortAsc ? 'asc' : 'desc']
        ]);
    }

    public function render()
    {
        return view('livewire.client.roles.member.users.add', [
            'users' => $this->users
        ]);
    }
}
