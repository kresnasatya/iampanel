<?php

namespace App\Http\Livewire;

use Livewire\Component;
use RistekUSDI\Kisara\User as KisaraUser;
use RistekUSDI\Kisara\UserClientRole as KisaraUserClientRole;
use RistekUSDI\Kisara\Client as KisaraClient;

class UserApplications extends Component
{
    public $user_id;
    public $available_roles;
    public $assigned_roles;
    public $effective_roles;

    public $selectedClient = null;

    public $selected_available_roles;
    public $selected_assigned_roles;

    public function mount($user)
    {
        $selected_user = (new KisaraUser(config('sso')))->get(array(
            'email' => $user['mail']['0'],
            'username' => $user['uid']['0']
        ));

        $this->user_id = !empty($selected_user) ? $selected_user['0']['id'] : '0';
        $this->available_roles = [];
        $this->assigned_roles = [];
        $this->effective_roles = [];
    }

    public function render()
    {
        $clients = (new KisaraClient(config('sso')))->get();
        
        return view('livewire.user-applications', [
            'clients' => $clients
        ]);
    }

    public function updatedSelectedClient($client_id)
    {   
        // Available roles
        $this->available_roles = (new KisaraUserClientRole(config('sso')))->getAvailableRoles($this->user_id, $client_id);

        // Assigned roles
        $this->assigned_roles = (new KisaraUserClientRole(config('sso')))->getAssignedRoles($this->user_id, $client_id);

        // Efective roles
        $this->effective_roles = (new KisaraUserClientRole(config('sso')))->getEffectiveRoles($this->user_id, $client_id);
    }

    public function storeAssignedClientRoles()
    {
        $this->validate([
            'selected_available_roles' => 'required'
        ]);

        $raw_roles = $this->selected_available_roles;
        $roles = [];
        foreach ($raw_roles as $raw_role) {
            $roles[] = json_decode($raw_role, true);
        }

        // TODO:
        // Give better response if success or error
        (new KisaraUserClientRole(config('sso')))->storeAssignedRoles($this->user_id, $this->selectedClient, $roles);

        // Available roles
        $this->available_roles = (new KisaraUserClientRole(config('sso')))->getAvailableRoles($this->user_id, $this->selectedClient);

        // Assigned roles
        $this->assigned_roles = (new KisaraUserClientRole(config('sso')))->getAssignedRoles($this->user_id, $this->selectedClient);

        // Efective roles
        $this->effective_roles = (new KisaraUserClientRole(config('sso')))->getEffectiveRoles($this->user_id, $this->selectedClient);
    }

    public function deleteAssignedClientRoles()
    {
        $this->validate([
            'selected_assigned_roles' => 'required'
        ]);
        
        $raw_roles = $this->selected_assigned_roles;
        $roles = [];
        foreach ($raw_roles as $raw_role) {
            $roles[] = json_decode($raw_role, true);
        }
        
        // TODO:
        // Give better response if success or error
        (new KisaraUserClientRole(config('sso')))->deleteAssignedRoles($this->user_id, $this->selectedClient, $roles);

        // Available roles
        $this->available_roles = (new KisaraUserClientRole(config('sso')))->getAvailableRoles($this->user_id, $this->selectedClient);

        // Assigned roles
        $this->assigned_roles = (new KisaraUserClientRole(config('sso')))->getAssignedRoles($this->user_id, $this->selectedClient);

        // Efective roles
        $this->effective_roles = (new KisaraUserClientRole(config('sso')))->getEffectiveRoles($this->user_id, $this->selectedClient);
    }
}
