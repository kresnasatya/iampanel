<?php

namespace App\Http\Livewire\Group\ApplicationMapper;

use Livewire\Component;
use Livewire\WithPagination;
use RistekUSDI\Kisara\Group as KisaraGroup;
use RistekUSDI\Kisara\GroupClientRole as KisaraGroupClientRole;

class Table extends Component
{
    use WithPagination;

    public $group_id;
    public $available_roles;
    public $assigned_roles;
    public $effective_roles;

    public $selectedClient = null;

    public $selected_available_roles;
    public $selected_assigned_roles;

    public $perPage = 10;
    public $search = '';

    protected $listeners = ['refreshApplicationMapperTable', 'deleteAssignedClientRole'];

    public function mount($group_id)
    {
        $this->group_id = $group_id;
        $this->available_roles = [];
        $this->assigned_roles = [];
        $this->effective_roles = [];
    }

    public function refreshApplicationMapperTable()
    {
        $this->perPage = 10;
        $this->search = '';
    }

    public function getRoleMappingsProperty()
    {
        $raw_role_mappings = (new KisaraGroup(config('sso')))->getRoleMappings($this->group_id);

        $role_mappings = [];
        foreach ($raw_role_mappings as $key => $role_mapper) {
            if ($key === 'realmMappings') {
                $realm_items = [];
                foreach ($raw_role_mappings[$key] as $realm_role_mapper) {
                    $realm_items['client'] = '';
                    $realm_items['client_id'] = $realm_role_mapper['containerId'];
                    $realm_items['is_client_role'] = (bool) $realm_role_mapper['clientRole'];
                    $realm_items['role_id'] = $realm_role_mapper['id'];
                    $realm_items['role_name'] = $realm_role_mapper['name'];
                }
                $role_mappings[] = $realm_items;
            }

            if ($key === 'clientMappings') {
                foreach ($raw_role_mappings[$key] as $client_role_mapper) {
                    foreach ($client_role_mapper['mappings'] as $_key => $client_role) {
                        $item = [];

                        $item['client'] = $client_role_mapper['client'];
                        $item['client_id'] = $client_role['containerId'];
                        $item['is_client_role'] = (bool) $client_role['clientRole'];
                        $item['role_id'] = $client_role['id'];
                        $item['role_name'] = $client_role['name'];

                        $role_mappings[] = $item;
                    }
                }
            }
        }

        $role_mappings = collect($role_mappings)->filter(function ($item) {
            $search = strtolower($this->search);
            return preg_match("/{$search}/", strtolower($item['role_name'])) || preg_match("/{$search}/", strtolower($item['client']));
        })->paginate($this->perPage);

        return $role_mappings;
    }

    public function deleteAssignedClientRoleConfirm($client_id, $raw_role)
    {
        $decode_role = json_decode($raw_role, true);

        $this->dispatchBrowserEvent('delete-client-role:confirm', [
            'title' => "Peringatan!",
            'message' => "Anda yakin akan menghapus peran {$decode_role['role_name']} dari aplikasi {$decode_role['client']}?",
            'client_id' => $client_id,
            'role' => $raw_role
        ]);
    }

    public function deleteAssignedClientRole($client_id, $raw_role)
    {
        $decode_role = json_decode($raw_role, true);
        $role = [
            'id' => $decode_role['role_id'],
            'name' => $decode_role['role_name'],
        ];

        $roles = [$role];

        $response = (new KisaraGroupClientRole(config('sso')))->deleteAssignedRoles($this->group_id, $client_id, $roles);

        if ($response['code'] === 204) {
            $this->dispatchBrowserEvent('delete-client-role:ok', [
                'message' => "Peran berhasil dihapus"
            ]);
        } else {
            $this->dispatchBrowserEvent('delete-client-role:error', [
                'message' => "Peran gagal dihapus"
            ]);
        }
    }

    public function render()
    {
        return view('livewire.group.application-mapper.table', [
            'role_mappings' => $this->role_mappings,
        ]);
    }
}
