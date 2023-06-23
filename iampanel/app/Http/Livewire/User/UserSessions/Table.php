<?php

namespace App\Http\Livewire\User\UserSessions;

use Livewire\Component;
use Livewire\WithPagination;
use RistekUSDI\Kisara\User as KisaraUser;
use RistekUSDI\Kisara\Session as KisaraSession;

class Table extends Component
{
    use WithPagination;

    public $user_id;
    public $username;

    public $perPage = 10;
    public $search = '';

    protected $listeners = ['refreshUserSessionsTable', 'deleteUserSession', 'deleteUserSessions'];

    public function mount($user)
    {
        $this->user_id = $user['id'];
        $this->username = $user['username'];
    }

    public function refreshUserSessionsTable()
    {
        $this->perPage = 10;
        $this->search = '';
    }

    public function deleteUserSessionsConfirm()
    {
        $this->dispatchBrowserEvent('delete-user-sessions:confirm', [
            'title' => "Peringatan!",
            'message' => "Anda yakin akan mengakhiri semua sesi aktif aplikasi pada username {$this->username}?",
        ]);
    }

    public function deleteUserSessions()
    {
        $response = (new KisaraUser(config('sso')))->logout($this->user_id);

        if ($response['code'] === 204) {
            $this->dispatchBrowserEvent('delete-user-sessions:ok', [
                'message' => "Sesi berhasil berhasil dihapus"
            ]);
        } else {
            $this->dispatchBrowserEvent('delete-user-sessions:error', [
                'message' => "Sesi gagal dihapus"
            ]);
        }
    }

    public function deleteUserSessionConfirm($session_id, $username, $clients)
    {
        $clients = implode(", ", json_decode($clients, true));

        $this->dispatchBrowserEvent('delete-user-session:confirm', [
            'title' => "Peringatan!",
            'message' => "Anda yakin akan mengakhiri sesi aktif aplikasi {$clients} pada username {$username}?",
            'session_id' => $session_id,
        ]);
    }

    public function deleteUserSession($session_id)
    {
        $response = (new KisaraSession(config('sso')))->delete($session_id);

        if ($response['code'] === 204) {
            $this->dispatchBrowserEvent('delete-user-session:ok', [
                'message' => "Sesi berhasil diakhiri"
            ]);
        } else {
            $this->dispatchBrowserEvent('delete-user-session:error', [
                'message' => "Sesi gagal diakhiri"
            ]);
        }
    }

    public function getUserSessionsProperty()
    {
        $raw_user_sessions = (new KisaraUser(config('sso')))->getUserSessions($this->user_id);

        $user_sessions = collect($raw_user_sessions)->filter(function ($item) {
            $search = strtolower($this->search);
            return !empty($search) ? array_search($search, $item['clients']) : $item;
        })->paginate($this->perPage);

        return $user_sessions;
    }

    // If current authenticated user is in user sessions
    // then current authenticated user cannot remove his/her sessions.
    public function getCurrentUserProperty()
    {
        $current_user = false;
        if ($this->username === auth('imissu-web')->user()->username) {
            $current_user = true;
        }
        return $current_user;
    }

    public function render()
    {
        return view('livewire.user.user-sessions.table', [
            'user_sessions' => $this->user_sessions,
            'current_user' => $this->current_user,
        ]);
    }
}
