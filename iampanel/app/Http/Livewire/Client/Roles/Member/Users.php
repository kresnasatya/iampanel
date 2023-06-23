<?php

namespace App\Http\Livewire\Client\Roles\Member;

use Livewire\Component;
use RistekUSDI\Kisara\Client as KisaraClient;
use RistekUSDI\Kisara\ClientRole as KisaraClientRole;
use RistekUSDI\Kisara\UserClientRole as KisaraUserClientRole;
use RistekUSDI\Kisara\Role as KisaraRole;

class Users extends Component
{
    public $first = 0;
    public $perPage = 10;
    public $clientId;
    public $clientName;
    public $roleId;
    public $roleName;

    protected $listeners = ['refreshUsersTable', 'deleteUserFromClientRole', 'deleteUserOk', 'deleteUserFailed'];

    public function mount($clientId, $roleId)
    {
        // $clientId adalah id dari client bukan "nama" dari client.
        $this->clientId = $clientId;
        $client = (new KisaraClient(config('sso')))->findById($clientId);
        $this->clientName = $client['clientId'];
        $this->roleId = $roleId;
        $role = (new KisaraRole(config('sso')))->findById($roleId);
        $this->roleName = !empty($role) ? $role['name'] : 'Unknown';
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
        $this->searchUserType = '';
        $this->sortField = 'firstName';
        $this->sortAsc = true;
    }

    public function deleteUserConfirm($user_id, $user_name)
    {
        $this->dispatchBrowserEvent('delete-user:confirm', [
            'title' => "Peringatan!",
            'message' => "Anda yakin akan menghapus {$user_name} dari peran {$this->roleName} aplikasi {$this->clientName}?",
            'user_id' => $user_id,
            'user_name' => $user_name
        ]);
    }

    public function deleteUserFromClientRole($user_id, $user_name)
    {
        $assigned_roles = (new KisaraUserClientRole(config('sso')))->getAssignedRoles($user_id, $this->clientId);
        
        $selected_role = [];
        foreach ($assigned_roles as $assigned_roles) {
            if ($assigned_roles['name'] == $this->roleName) {
                $selected_role = $assigned_roles;
            }
        }

        $roles = [$selected_role];
        
        $response = (new KisaraUserClientRole(config('sso')))->deleteAssignedRoles($user_id, $this->clientId, $roles);

        if ($response['code'] === 204) {
            $this->emit('deleteUserOk', array(
                'user_name' => $user_name
            ));
        } else {
            $this->emit('deleteUserFailed', array(
                'user_name' => $user_name
            ));
        }
    }

    public function deleteUserOk($data)
    {
        $this->dispatchBrowserEvent('delete-user:ok', [
            'message' => "{$data['user_name']} berhasil dihapus dari peran {$this->roleName}"
        ]);
    }

    public function deleteUserFailed($data)
    {
        $this->dispatchBrowserEvent('delete-user:failed', [
            'message' => "{$data['user_name']} gagal dihapus dari peran {$this->roleName}"
        ]);
    }

    public function getUsersProperty()
    {
        $params = [
            'first' => $this->first,
            'max' => $this->perPage,
        ];
        
        $users = (new KisaraClientRole(config('sso')))->getUsers($this->clientId, rawurlencode($this->roleName), $params);
        
        return collect($users);
    }
    
    public function render()
    {
        return view('livewire.client.roles.member.users', [
            'users' => $this->users
        ]);
    }
}
