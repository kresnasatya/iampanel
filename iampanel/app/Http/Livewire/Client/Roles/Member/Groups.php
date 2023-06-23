<?php

namespace App\Http\Livewire\Client\Roles\Member;

use Livewire\Component;
use Livewire\WithPagination;
use RistekUSDI\Kisara\Group as KisaraGroup;
use RistekUSDI\Kisara\GroupClientRole as KisaraGroupClientRole;
use RistekUSDI\Kisara\Client as KisaraClient;
use RistekUSDI\Kisara\ClientRole as KisaraClientRole;
use RistekUSDI\Kisara\Role as KisaraRole;

class Groups extends Component
{
    use WithPagination;

    public $clientId;
    public $clientName;
    public $roleId;
    public $first = 0;
    public $perPage = 10;

    protected $listeners = ['refreshGroupsTable', 'deleteGroupFromClientRole', 'deleteGroupOk', 'deleteGroupFailed'];

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

    public function refreshGroupsTable()
    {
        $this->first = 0;
        $this->perPage = 10;
    }

    public function deleteGroupConfirm($group_id, $group_name)
    {
        $this->dispatchBrowserEvent('delete-group:confirm', [
            'title' => "Peringatan!",
            'message' => "Anda yakin akan menghapus kelompok {$group_name} dari peran {$this->roleName} aplikasi {$this->clientName}?",
            'group_id' => $group_id,
            'group_name' => $group_name
        ]);
    }

    public function deleteGroupFromClientRole($group_id, $group_name)
    {
        $assigned_roles = (new KisaraGroupClientRole(config('sso')))->getAssignedRoles($group_id, $this->clientId);
        
        $selected_role = [];
        foreach ($assigned_roles as $assigned_roles) {
            if ($assigned_roles['name'] == $this->roleName) {
                $selected_role = $assigned_roles;
            }
        }

        $roles = [$selected_role];
        
        $response = (new KisaraGroupClientRole(config('sso')))->deleteAssignedRoles($group_id, $this->clientId, $roles);
        if ($response['code'] === 204) {
            $this->emit('deleteGroupOk', array(
                'group_name' => $group_name
            ));
        } else {
            $this->emit('deleteGroupFailed', array(
                'group_name' => $group_name
            ));
        }
    }

    public function deleteGroupOk($data)
    {
        $this->dispatchBrowserEvent('delete-group:ok', [
            'message' => "{$data['group_name']} berhasil dihapus dari peran {$this->roleName}"
        ]);
    }

    public function deleteGroupFailed($data)
    {
        $this->dispatchBrowserEvent('delete-group:failed', [
            'message' => "{$data['group_name']} gagal dihapus dari peran {$this->roleName}"
        ]);
    }

    public function getGroupsProperty()
    {
        $params = [
            'first' => $this->first,
            'max' => $this->perPage,
        ];

        $groups = (new KisaraClientRole(config('sso')))->getGroups($this->clientId, rawurlencode($this->roleName), $params);
        
        return collect($groups);
    }

    public function render()
    {
        return view('livewire.client.roles.member.groups', [
            'groups' => $this->groups
        ]);
    }
}
