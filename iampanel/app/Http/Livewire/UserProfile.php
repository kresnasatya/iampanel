<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use RistekUSDI\Kisara\User as KisaraUser;
use Illuminate\Support\Facades\Storage;

class UserProfile extends Component
{
    use WithFileUploads;
    const CONFIGURE_TOTP = 'CONFIGURE_TOTP';
    const WEBAUTHN_REGISTER_PASSWORDLESS = 'webauthn-register-passwordless';
    public $user = [];
    public $configureTOTP = false;
    public $webAuthnRegisterPasswordless = false;

    // Attributes
    public $photo, $picture;

    protected $rules = [
        'user.email' => 'required',
        'user.lastName' => 'required',
        'user.attributes.nik.0' => 'required|min:16|max:16|regex:/([A-Za-z0-9-.])/i', // regex alphanumeric include dash and dot character,
        'user.attributes.gender.0' => 'required',
        'user.attributes.address.0' => 'required',
        'user.attributes.phone_number.0' => 'required',
    ];

    protected $messages = [
        'user.email.required' => 'Email wajib diisi.',
        'user.lastName.required' => 'Nama wajib diisi.',
        'user.attributes.nik.0.required' => 'NIK wajib diisi.',
        'user.attributes.nik.0.min' => 'NIK minimal 16 karakter',
        'user.attributes.gender.0.required' => 'Jenis Kelamin wajib diisi.',
        'user.attributes.address.0.required' => 'Alamat wajib diisi.',
        'user.attributes.phone_number.0.required' => 'Nomor Ponsel wajib diisi.',
    ];

    public function mount()
    {
        $user = (new KisaraUser(config('sso')))->findById(auth('imissu-web')->user()->sub);

        if (!empty($user)) {
            $this->user = $user;
            if (isset($user['requiredActions'])) {
                $this->configureTOTP = in_array(self::CONFIGURE_TOTP, $user['requiredActions']) ? true : false;
                $this->webAuthnRegisterPasswordless = in_array(self::WEBAUTHN_REGISTER_PASSWORDLESS, $user['requiredActions']) ? true : false;
            }
            $this->picture = isset($this->user['attributes']['picture']) ? user_profile_picture($this->user['attributes']['picture']['0']) : user_profile_picture();
        }
    }

    public function updatedPhoto()
    {
        $this->validate([
            'photo' => 'image|max:5024',
        ]);

        // 1. Store photo in local disk profiles directory
        $photo_path = '';
        $data = [
            'email' => $this->user['email'],
            'firstName' => $this->user['firstName'],
            'lastName' => $this->user['lastName'],
            'attributes' => [
                'nik' => isset($this->user['attributes']['nik']) ? $this->user['attributes']['nik']['0'] : '',
                'phone_number' => isset($this->user['attributes']['phone_number']) ? $this->user['attributes']['phone_number']['0'] : '',
                'address' => isset($this->user['attributes']['address']) ? $this->user['attributes']['address']['0'] : '',
                'gender' => isset($this->user['attributes']['gender']) ? $this->user['attributes']['gender']['0'] : '',
                'picture' => $photo_path,
            ]
        ];
        $result = (new KisaraUser(config('sso')))->update($this->user['id'], $data);

        if ($result['code'] == 204) {
            $this->dispatchBrowserEvent('toast:ok', [
                'message' => "Berhasil mengunggah foto!"
            ]);
        } else {
            \Log::info($result);
            $this->dispatchBrowserEvent('toast:error', [
                'message' => "Gagal mengunggah foto!"
            ]);
        }

        // 2. Set value $this->picture
        $this->picture = user_profile_picture();

        // 3. Delete photo from livewire-tmp directory
        $this->photo->delete($this->photo->getPath());
    }

    public function submit()
    {
        $this->validate();

        $data = [
            'email' => $this->user['email'],
            'firstName' => $this->user['firstName'],
            'lastName' => $this->user['lastName'],
            'attributes' => [
                'nik' => $this->user['attributes']['nik']['0'],
                'phone_number' => $this->user['attributes']['phone_number']['0'],
                'address' => $this->user['attributes']['address']['0'],
                'gender' => $this->user['attributes']['gender']['0'],
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

        if ($result['code'] == 204) {
            $this->dispatchBrowserEvent('toast:ok', [
                'message' => "Profil berhasil diperbaharui!"
            ]);
        } else {
            \Log::info($result);
            $this->dispatchBrowserEvent('toast:error', [
                'message' => "Profil gagal diperbaharui!"
            ]);
        }
    }

    public function render()
    {
        return view('livewire.user-profile');
    }
}
