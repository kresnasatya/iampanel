<?php

namespace App\Http\Livewire;

use Livewire\Component;
use RistekUSDI\Kisara\User as KisaraUser;

class UserActiveAppSessions extends Component
{
    private $config;
    
    public function __construct()
    {
        $this->config = [
            'base_url' => config('sso.base_url'),
            'realm' => config('sso.realm'),
            'access_token' => session()->get('_sso_token.access_token')
        ];
    }

    public function getActiveAppSessionsProperty()
    {
        return (new KisaraUser($this->config))->getActiveAppSessions();
    }


    public function render()
    {
        return view('livewire.user-active-app-sessions', [
            'active_app_sessions' => $this->active_app_sessions,
        ]);
    }
}
