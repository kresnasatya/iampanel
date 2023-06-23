<?php

namespace App\Http\Livewire\Client\Roles\Member;

use Livewire\Component;
use Livewire\WithPagination;
use RistekUSDI\Kisara\Client AS KisaraClient;
use RistekUSDI\Kisara\Role as KisaraRole;

class Index extends Component
{
    use WithPagination;

    public $clientId;
    public $clientName;
    public $clientProtocol;
    public $roleId;
    public $roleName;

    public function mount($client_id, $role_id)
    {
        $this->clientId = $client_id;
        $client = (new KisaraClient(config('sso')))->findById($client_id);
        $this->clientName = !empty($client['name']) ? $client['name'] : $client['clientId'];
        $this->clientProtocol = $client['protocol'];
        $this->roleId = $role_id;
        $role = (new KisaraRole(config('sso')))->findById($role_id);
        $this->roleName = !empty($role) ? $role['name'] : 'Unknown';
    }

    public function render()
    {
        return view('livewire.client.roles.member.index');
    }
}
