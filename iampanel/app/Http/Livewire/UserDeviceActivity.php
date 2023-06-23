<?php

namespace App\Http\Livewire;

use Livewire\Component;
use RistekUSDI\Kisara\DeviceActivity as KisaraDeviceActivity;

class UserDeviceActivity extends Component
{
    private $config;
    protected $listeners = ['endAllSession', 'endAllSessionOk', 'endAllSessionError', 'endSession', 'endSessionOk', 'endSessionError'];

    public function __construct()
    {
        $this->config = [
            'base_url' => config('sso.base_url'),
            'realm' => config('sso.realm'),
            'access_token' => session()->get('_sso_token.access_token')
        ];
    }

    public function getDeviceActivityProperty()
    {
        return (new KisaraDeviceActivity($this->config))->get();
    }

    public function endAllSessionConfirm()
    {
        $this->dispatchBrowserEvent('swal-end-all-session:confirm', [
            'title' => "Peringatan!",
            'message' => "Anda yakin akan mengakhiri semua sesi dari perangkat yang Anda gunakan untuk login ke IMISSU2?",
        ]);
    }

    public function endAllSession()
    {
        $response = (new KisaraDeviceActivity($this->config))->endAllSession();

        if ($response['code'] === 204) {
            $this->emit('endAllSessionOk');
        } else {
            $this->emit('endAllSessionError');
        }
    }

    public function endAllSessionOk()
    {
        $this->dispatchBrowserEvent('swal-end-all-session:ok', [
            'title' => 'Berhasil!',
            'message' => "Semua sesi berhasil diakhiri!"
        ]);
    }

    public function endAllSessionError()
    {
        $this->dispatchBrowserEvent('swal:error', [
            'title' => 'Gagal!',
            'message' => "Semua sesi gagal diakhiri!"
        ]);
    }

    public function endSessionConfirm($session, $device)
    {
        $session = json_decode($session, true);
        $device = json_decode($device, true);
        $device_name = "{$device['os']} {$device['osVersion']}";
        $browser_name = isset($session['browser']) ? $session['browser'] : '';
        $device_browser = "{$device_name} {$browser_name}";
        $this->dispatchBrowserEvent('swal:confirm', [
            'title' => "Peringatan!",
            'message' => "Anda yakin akan mengakhiri sesi login dari perangkat {$device_browser}?",
            'session_id' => $session['id'],
        ]);
    }

    public function endSession($session_id)
    {
        $response = (new KisaraDeviceActivity($this->config))->endSession($session_id);

        if ($response['code'] === 204) {
            $this->emit('endSessionOk');
        } else {
            $this->emit('endSessionError');
        }
    }

    public function endSessionOk()
    {
        $this->dispatchBrowserEvent('swal-end-session:ok', [
            'title' => 'Berhasil!',
            'message' => "Sesi berhasil diakhiri!"
        ]);
    }

    public function endSessionError()
    {
        $this->dispatchBrowserEvent('swal-end-session:error', [
            'title' => 'Gagal!',
            'message' => "Sesi gagal diakhiri!"
        ]);
    }

    public function render()
    {
        $device_sessions = array_column($this->device_activity, 'sessions');
        $session_ids = [];
        foreach ($device_sessions as $sessions) {
            foreach ($sessions as $session) {
                $session_ids[] = $session['id'];
            }
        }
        return view('livewire.user-device-activity', [
            'device_activity' => $this->device_activity,
            'total_session' => count($session_ids)
        ]);
    }
}
