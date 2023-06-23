<?php

namespace App\Http\Livewire;

use App\Http\Requests\MyPasswordRequest;
use Livewire\Component;
use RistekUSDI\Kisara\User as KisaraUser;

class UserCredentials extends Component
{
    const PASSWORD = 'password';
    public $password;
    public $password_confirmation;

    // protected $rules = [
    //     'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->letters()->numbers()],
    // ];

    // protected $messages = [
    //     'password.required' => 'Kata sandi baru wajib diisi.',
    //     'password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
    //     'password.min' => 'Kata sandi baru minimal 8 karakter.',
    //     'password.mixedCase' => 'Kata sandi baru wajib berisi satu huruf besar dan satu huruf kecil.'
    // ];

    public function clearForm()
    {
        $this->resetValidation();
        $this->password = '';
        $this->password_confirmation = '';
    }

    public function rules()
    {
        return (new MyPasswordRequest())->rules();
    }

    public function messages()
    {
        return (new MyPasswordRequest())->messages();
    }

    public function submit()
    {
        $this->validate();

        $data = array(
            'type' => self::PASSWORD,
            'value' => $this->password,
            'temporary' => false,
        );

        $result = (new KisaraUser(config('sso')))->resetCredentials(auth('imissu-web')->user()->sub, $data);

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
        return view('livewire.user-credentials');
    }
}
