<?php

namespace App\Http\Livewire\User;

use Livewire\Component;
use RistekUSDI\Kisara\User as KisaraUser;

class View extends Component
{
    public $user;
    public $user_type;

    public function mount($user_id)
    {
        $this->user = (new KisaraUser(config('sso')))->findById($user_id);

        $user_type_id = '0';
        $this->user_type = search_user_type($user_type_id);
    }

    public function render()
    {
        return view('livewire.user.view');
    }
}
