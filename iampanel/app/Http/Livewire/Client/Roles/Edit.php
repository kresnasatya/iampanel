<?php

namespace App\Http\Livewire\Client\Roles;

use Livewire\Component;
use RistekUSDI\Kisara\Client as KisaraClient;
use RistekUSDI\Kisara\Role as KisaraRole;

class Edit extends Component
{
    public $client_id;
    public $client_name;
    public $client_protocol;
    public $role;

    protected $rules = [
        'role.name' => 'required'
    ];

    protected $messages = [
        'role.name.required' => 'Nama peran wajib diisi.'
    ];

    public function mount($client_id, $role_id)
    {
        $role = (new KisaraRole(config('sso')))->findById($role_id);
        if (empty($role)) {
            abort(404);
        } else {
            $this->role = $role;
            $this->client_id = $client_id;
            $client = (new KisaraClient(config('sso')))->findById($this->client_id);    
            $this->client_name = $client['clientId'];
            $this->client_protocol = $client['protocol'];
        }
    }

    public function submit()
    {
        $this->validate();

        $response = (new KisaraRole(config('sso')))->update($this->role['id'], [
            'name' => trim($this->role['name'])
        ]);
        
        if ((int) $response['code'] !== 204) {
            $message = 'Gagal memperbaharui data!';
            if (isset($response['body']['errorMessage'])) {
                $message = $response['body']['errorMessage'];
            }
            $this->addError('errorMessage', $message);
        } else {
            $this->dispatchBrowserEvent('toast:ok', [
                'message' => "Peran berhasil diperbaharui!"
            ]);
        }
    }

    public function render()
    {
        return view('livewire.client.roles.edit');
    }
}
