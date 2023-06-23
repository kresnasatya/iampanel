<?php

namespace App\Http\Livewire\Group;

use Livewire\Component;
use RistekUSDI\Kisara\Group as KisaraGroup;

class Create extends Component
{
    public $group_name;

    protected $rules = [
        'group_name' => 'required'
    ];

    protected $messages = [
        'group_name.required' => 'Nama Kelompok wajib diisi.'
    ];

    public function submit()
    {
        $this->validate();

        $group_name = str_replace(" ", "-", strtolower(trim($this->group_name)));

        $response = (new KisaraGroup(config('sso')))->store([
            'name' => $group_name,
        ]);

        if ($response['code'] == 201) {
            $sa_group = (new KisaraGroup(config('sso')))->get([
                'search' => $group_name,
            ]);
            return redirect()->to("/groups/{$sa_group[0]['id']}/edit");
        } else {
            $this->addError('errorMessage', $response['body']['errorMessage']);
        }
    }

    public function render()
    {
        return view('livewire.group.create');
    }
}
