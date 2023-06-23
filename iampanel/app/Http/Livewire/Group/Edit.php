<?php

namespace App\Http\Livewire\Group;

use Livewire\Component;
use RistekUSDI\Kisara\Group as KisaraGroup;

class Edit extends Component
{
    public $group_id;
    public $group;
    public $group_name;
    public $previous_group_name;

    protected $rules = [
        'group_name' => 'required'
    ];

    protected $messages = [
        'group_name.required' => 'Nama Kelompok wajib diisi.'
    ];

    public function mount($group_id)
    {
        $this->group_id = $group_id;
        $this->group = (new KisaraGroup(config('sso')))->findById($this->group_id);
        $this->group_name = $this->group['name'];
        $this->previous_group_name = $this->group['name'];
    }

    public function submit()
    {
        $this->validate();
        if ($this->group_name !== $this->previous_group_name) {
            $group_name = str_replace(" ", "-", strtolower(trim($this->group_name)));
            $response = (new KisaraGroup(config('sso')))->update($this->group_id, [
                'name' => $group_name,
            ]);

            if ($response['code'] === 204) {
                return redirect()->to("/groups/{$this->group_id}/edit");
            } else {
                $this->addError('errorMessage', $response['body']['errorMessage']);
            }
        } else {
            $this->dispatchBrowserEvent('toast:ok', [
                'message' => "Berhasil disimpan!"
            ]);
        }
    }

    public function render()
    {
        return view('livewire.group.edit');
    }
}
