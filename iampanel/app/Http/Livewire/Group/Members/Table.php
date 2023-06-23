<?php

namespace App\Http\Livewire\Group\Members;

use Livewire\Component;
use Livewire\WithPagination;
use RistekUSDI\Kisara\Group as KisaraGroup;
use RistekUSDI\Kisara\UserGroup as KisaraUserGroup;
use App\Queries\SearchMembers;

class Table extends Component
{
    use WithPagination;

    public $group_id;
    public $first = 0;
    public $perPage = 10;
    public $sortField = 'firstName';
    public $sortAsc = true;
    public $search = '';

    protected $listeners = ['refreshMembersTable', 'deleteMemberFromGroup', 'deleteMemberOk', 'deleteMemberFailed'];

    public function mount($group_id)
    {
        $this->group_id = $group_id;
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
        if ($this->first < 0) {
            $this->first = 0;
        }
    }

    public function nextPage()
    {
        $this->first = $this->first + $this->perPage;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function refreshMembersTable()
    {
        $this->first = 0;
        $this->perPage = 10;
        $this->search = '';
        $this->sortField = 'firstName';
        $this->sortAsc = true;
    }

    public function deleteMemberConfirm($member_id, $member_username, $member_name)
    {
        $this->dispatchBrowserEvent('delete-member:confirm', [
            'title' => "Peringatan!",
            'message' => "Anda yakin akan menghapus {$member_name} dari kelompok {$this->group_name}?",
            'member_id' => $member_id,
            'member_username' => $member_username,
            'member_name' => $member_name
        ]);
    }

    public function deleteMemberFromGroup($member_id, $member_username, $member_name)
    {
        // 1. Attach keycloak user to keycloak group
        $result = (new KisaraUserGroup(config('sso')))->detach($member_id, $this->group_id);

        if ($result['code'] === 204) {
            $this->emit('deleteMemberOk', array(
                'member_name' => $member_name
            ));
        } else {
            $this->emit('deleteMemberFailed', array(
                'member_name' => $member_name
            ));
        }
    }

    public function deleteMemberOk($data)
    {
        $this->dispatchBrowserEvent('delete-member:ok', [
            'message' => "{$data['member_name']} berhasil dihapus dari peran {$this->group_name}"
        ]);
    }

    public function deleteMemberFailed($data)
    {
        $this->dispatchBrowserEvent('delete-member:failed', [
            'message' => "{$data['member_name']} gagal dihapus dari peran {$this->group_name}"
        ]);
    }

    public function getMembersProperty()
    {
        $params = [
            'first' => $this->first,
            'max' => $this->perPage,
        ];

        if (!empty($this->search)) {
            $members = SearchMembers::getBySQLQuery(array_merge($params, [
                'search' => $this->search,
                'group_id' => $this->group_id,
                'group_name' => $this->group_name
            ]));
        } else {
            $response = (new KisaraGroup(config('sso')))->members($this->group_id, $params);
            $members = [];
            if ($response['code'] === 200) {
                $members = $response['body'];
            }
        }

        return collect($members)->sortBy([
            [$this->sortField, $this->sortAsc ? 'asc' : 'desc']
        ]);
    }

    public function render()
    {
        return view('livewire.group.members.table', [
            'members' => $this->members,
        ]);
    }
}
