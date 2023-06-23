<?php

namespace App\Http\Livewire\Client\Roles;

use Livewire\Component;
use RistekUSDI\Kisara\Client as KisaraClient;
use RistekUSDI\Kisara\ClientRole as KisaraClientRole;

class Create extends Component
{
    public $client_id;
    public $client_name;
    public $client_protocol;
    public $role;

    protected $rules = [
        'role' => 'required'
    ];

    protected $messages = [
        'role.required' => 'Nama peran wajib diisi.'
    ];

    public function mount($client_id)
    {
        $this->client_id = $client_id;
        $client = (new KisaraClient(config('sso')))->findById($this->client_id);
        $this->client_name = $client['clientId'];
        $this->client_protocol = $client['protocol'];
    }

    public function submit()
    {
        $this->validate();

        $response = (new KisaraClientRole(config('sso')))->store($this->client_id, [
            'name' => trim($this->role),
        ]);
        
        if ((int) $response['code'] !== 201) {
            $this->addError('errorMessage', $response['body']['errorMessage']);
        } else {
            return redirect()->to("/clients/{$this->client_id}/edit#roles");
        }
    }

    public function render()
    {
        return view('livewire.client.roles.create');
    }
}
