<?php

namespace App\Http\Livewire\Client\UserSessions;

use Livewire\Component;
use RistekUSDI\Kisara\Client as KisaraClient;

class Index extends Component
{
    public $client_id;
    public $clientId;
    public $client_protocol;

    public function mount($client_id)
    {
        $this->client_id = $client_id;
        $client = (new KisaraClient(config('sso')))->findById($client_id);
        $this->clientId = $client['clientId'];
        $this->client_protocol = $client['protocol'];
    }

    public function render()
    {
        return view('livewire.client.user-sessions.index');
    }
}
