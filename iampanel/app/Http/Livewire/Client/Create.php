<?php

namespace App\Http\Livewire\Client;

use Livewire\Component;
use RistekUSDI\Kisara\Client as KisaraClient;

class Create extends Component
{
    public $clientId;
    public $protocol;
    // Determine if client type is public or confidential
    // true = public, false = confidential
    public $publicClient;
    public $rootUrl;

    protected $rules = [
        'clientId' => 'required',
    ];

    protected $messages = [
        'clientId.required' => 'Client ID wajib diisi.',
    ];

    public function submit()
    {
        $this->validate();

        $clientId = str_replace(" ", "-", strtolower($this->clientId));

        $response = (new KisaraClient(config('sso')))->store([
            'enabled' => 'true',
            'protocol' => $this->protocol,
            'clientId' => $clientId,
            'rootUrl' => $this->rootUrl,
            'publicClient' => $this->publicClient,
        ]);
        
        if ((int) $response['code'] !== 201) {
            $this->addError('errorMessage', $response['body']['errorMessage']);
        } else {
            $sa_clients = (new KisaraClient(config('sso')))->get([
                'clientId' => $clientId,
                'search' => 'true'
            ]);

            return redirect()->to("/clients/{$sa_clients[0]['id']}/edit");
        }
    }

    public function render()
    {
        return view('livewire.client.create');
    }
}
