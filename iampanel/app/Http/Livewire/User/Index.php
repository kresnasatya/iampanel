<?php

namespace App\Http\Livewire\User;

use Livewire\Component;
use RistekUSDI\Kisara\User as KisaraUser;
use App\Queries\SearchUsers;

class Index extends Component
{
    // use WithPagination;

    public $first = 0;
    public $perPage = 10;
    public $sortField = 'firstName';
    public $sortAsc = true;
    public $search = '';
    public $q = '';

    protected $listeners = ['refreshUsersTable'];

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortAsc = !$this->sortAsc;
        } else {
            $this->sortAsc = true;
        }

        $this->sortField = $field;
    }

    public function paginationView()
    {
        return 'includes._users_pagination';
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
        $this->first = 0;
    }

    public function updatingSearchUserType()
    {
        $this->first = 0;
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

    public function render()
    {
        return view('livewire.user.index', [
            'users' => $this->users,
            'user_types' => get_q_user_type_id()
        ]);
    }
}
