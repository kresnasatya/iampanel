<?php

namespace App\Http\Livewire\Group\Members;

use Livewire\Component;
use RistekUSDI\Kisara\Group as KisaraGroup;

class Index extends Component
{
    public $group_id;
    public $group_name;

    public function mount($group_id)
    {
        $this->group_id = $group_id;
        $group = (new KisaraGroup(config('sso')))->findById($group_id);
        $this->group_name = $group['name'];
    }

    public function render()
    {
        return view('livewire.group.members.index', [
            'group_id' => $this->group_id,
            'group_name' => $this->group_name,
        ]);
    }
}
