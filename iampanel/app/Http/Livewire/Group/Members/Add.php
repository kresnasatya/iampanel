<?php

namespace App\Http\Livewire\Group\Members;

use Livewire\Component;
use Livewire\WithPagination;
use RistekUSDI\Kisara\Group as KisaraGroup;
use RistekUSDI\Kisara\User as KisaraUser;
use RistekUSDI\Kisara\UserGroup as KisaraUserGroup;
use App\Queries\SearchUsers;

class Add extends Component
{
    use WithPagination;

    public $group_id;
    public $groupName;
    public $first = 0;
    public $perPage = 10;
    public $sortField = 'firstName';
    public $sortAsc = true;
    public $search = '';
    public $q = '';

    protected $listeners = ['refreshUsersTable', 'addOk', 'addFailed'];

    public function mount($group_id)
    {
        $this->group_id = $group_id;
        $group = (new KisaraGroup(config('sso')))->findById($group_id);
        $this->groupName = $group['name'];
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

    public function addUserToGroup($user_id, $username, $user_name)
    {
        // Attach keycloak user to keycloak group
        $result = (new KisaraUserGroup(config('sso')))->attach($user_id, $this->group_id);

        if ($result['code'] === 204) {
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
            'message' => "{$data['user_name']} berhasil ditambahkan ke kelompok {$this->groupName}"
        ]);
    }

    public function addFailed($data)
    {
        $this->dispatchBrowserEvent('add:failed', [
            'message' => "{$data['user_name']} gagal ditambahkan ke kelompok {$this->groupName}"
        ]);
    }

    public function render()
    {
        return view('livewire.group.members.add', [
            'users' => $this->users,
        ]);
    }
}
