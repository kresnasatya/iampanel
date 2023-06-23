<?php

namespace App\Http\Livewire\User;

use App\Http\Requests\MyPasswordRequest;
use Livewire\Component;
use RistekUSDI\Kisara\User as KisaraUser;

class Credentials extends Component
{
    const PASSWORD = 'password';
    public $user;
    public $password;
    public $password_confirmation;

    public function mount($user)
    {
        $this->user = $user;
    }

    public function rules()
    {
        return (new MyPasswordRequest())->rules();
    }

    public function messages()
    {
        return (new MyPasswordRequest())->messages();
    }

    public function clearForm()
    {
        $this->resetValidation();
        $this->password = '';
        $this->password_confirmation = '';
    }

    public function submit()
    {
        $this->validate();

        $data = array(
            'type' => self::PASSWORD,
            'value' => $this->password,
            'temporary' => true,
        );

        $result = (new KisaraUser(config('sso')))->resetCredentials($this->user['id'], $data);

        if ($result['code'] === 204) {
            $this->dispatchBrowserEvent('toast:ok', [
                'message' => "Atur ulang kata sandi berhasil!"
            ]);
            $this->clearForm();
        } else {
            $this->dispatchBrowserEvent('toast:error', [
                'message' => "Atur ulang kata sandi gagal!"
            ]);
        }
    }

    public function render()
    {
        return view('livewire.user.credentials');
    }
}
