<?php

namespace App\Http\Livewire\Group\ApplicationMapper;

use Livewire\Component;
use RistekUSDI\Kisara\Group as KisaraGroup;

class Index extends Component
{
    public $group_id;
    public $group_name;

    public function mount($group_id)
    {
        $this->group_id = $group_id;
        $group = (new KisaraGroup(config('sso')))->findById($this->group_id);
        $this->group_name = $group['name'];
    }

    public function render()
    {
        return view('livewire.group.application-mapper.index');
    }
}
