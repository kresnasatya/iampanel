<?php

namespace App\Http\Livewire\User;

use Livewire\Component;
use RistekUSDI\Kisara\User as KisaraUser;

class Edit extends Component
{
    const CONFIGURE_TOTP = 'CONFIGURE_TOTP';
    const WEBAUTHN_REGISTER_PASSWORDLESS = 'webauthn-register-passwordless';
    public $user;
    public $user_types;
    public $name;
    public $nik;
    public $email;
    public $birth_place;
    public $birth_date;
    public $telephone_number;
    public $mobile;
    public $identifier;
    public $username;
    public $address;
    public $gender_type;
    public $configureTOTP = false;
    public $webAuthnRegisterPasswordless = false;

    protected $rules = [
        'user.firstName' => 'required',
        'user.lastName' => 'required',
        'user.email' => 'required|regex:/^.+@.+$/i',
        'user.attributes.nik.0' => 'required|min:16|max:16|regex:/([A-Za-z0-9-.])/i', // regex alphanumeric include dash and dot character,
        'user.attributes.gender.0' => 'required',
        'user.attributes.address.0' => 'required',
        'user.attributes.phone_number.0' => 'required',
    ];

    protected $messages = [
        'user.firstName.required' => 'NIP / NIM wajib diisi.',
        'user.lastName.required' => 'Nama wajib diisi.',
        'user.email.required' => 'Email wajib diisi.',
        'user.attributes.nik.0.required' => 'NIK wajib diisi.',
        'user.attributes.nik.0.min' => 'NIK minimal 16 karakter',
        'user.attributes.gender.0.required' => 'Jenis Kelamin wajib diisi.',
        'user.attributes.address.0.required' => 'Alamat wajib diisi.',
        'user.attributes.phone_number.0.required' => 'Nomor Ponsel wajib diisi.',
    ];

    public function mount($user)
    {
        $this->user = $user;
        if (isset($user['requiredActions'])) {
            $this->configureTOTP = in_array(self::CONFIGURE_TOTP, $user['requiredActions']) ? true : false;
            $this->webAuthnRegisterPasswordless = in_array(self::WEBAUTHN_REGISTER_PASSWORDLESS, $user['requiredActions']) ? true : false;
        }
    }

    public function submit()
    {
        $this->validate();

        $data = [
            'email' => $this->user['email'],
            'firstName' => $this->user['firstName'],
            'lastName' => $this->user['lastName'],
            'enabled' => $this->user['enabled'],
            'attributes' => [
                'nik' => isset($this->user['attributes']['nik']) ? $this->user['attributes']['nik']['0'] : '',
                'phone_number' => isset($this->user['attributes']['phone_number']) ? $this->user['attributes']['phone_number']['0'] : '',
                'address' => isset($this->user['attributes']['address']) ? $this->user['attributes']['address']['0'] : '',
                'gender' => isset($this->user['attributes']['gender']) ? $this->user['attributes']['gender']['0'] : '',
                'picture' => isset($this->user['attributes']['picture']) ? $this->user['attributes']['picture']['0'] : '',
            ]
        ];

        $requiredActions = [];

        if ($this->configureTOTP) {
            array_push($requiredActions, self::CONFIGURE_TOTP);
        }

        if ($this->webAuthnRegisterPasswordless) {
            array_push($requiredActions, self::WEBAUTHN_REGISTER_PASSWORDLESS);
        }

        $data['requiredActions'] = $requiredActions;

        $result = (new KisaraUser(config('sso')))->update($this->user['id'], $data);

        if ($result['code'] === 204) {
            $this->dispatchBrowserEvent('toast:ok', [
                'message' => "Berhasil disimpan!"
            ]);
        } else {
            $this->dispatchBrowserEvent('toast:error', [
                'message' => "Gagal disimpan!"
            ]);
        }
    }

    public function render()
    {
        return view('livewire.user.edit');
    }
}
