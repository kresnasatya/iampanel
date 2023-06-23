<?php

namespace App\Http\Livewire\Client\UserSessions;

use Livewire\Component;
use Livewire\WithPagination;
use RistekUSDI\Kisara\Client as KisaraClient;
use RistekUSDI\Kisara\Session as KisaraSession;

class Table extends Component
{
    use WithPagination;

    public $client_id;
    public $clientId;
    public $first = 0;
    public $perPage = 10;
    public $search = '';

    protected $listeners = ['refreshUserSessionsTable', 'endSession', 'endSessionOk', 'endSessionError'];

    public function mount($client_id, $clientId)
    {
        $this->client_id = $client_id;
        $this->clientId = strtoupper($clientId);
    }

    public function paginationView()
    {
        return 'includes._pagination';
    }

    public function previousPage()
    {
        $this->first = $this->first - $this->perPage;
    }

    public function nextPage()
    {
        $this->first = $this->first + $this->perPage;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function refreshUserSessionsTable()
    {
        $this->first = 0;
        $this->perPage = 10;
        $this->search = '';
    }

    public function endSessionConfirm($user_session_id, $user_session_username)
    {
        $this->dispatchBrowserEvent('swal:confirm', [
            'title' => "Peringatan!",
            'message' => "Anda yakin akan mengakhiri sesi username {$user_session_username} dari aplikasi {$this->clientId}?",
            'user_session_id' => $user_session_id,
            'user_session_username' => $user_session_username
        ]);
    }

    public function endSession($user_session_id, $user_session_username)
    {
        $response = (new KisaraSession(config('sso')))->delete($user_session_id);

        if ($response['code'] === 204) {
            $this->emit('endSessionOk', array(
                'user_session_username' => $user_session_username
            ));
        } else {
            $this->emit('endSessionError', array(
                'user_session_username' => $user_session_username
            ));
        }
    }

    public function endSessionOk($data)
    {
        $this->dispatchBrowserEvent('swal:ok', [
            'title' => 'Berhasil!',
            'message' => "Sesi username {$data['user_session_username']} berhasil diakhiri dari aplikasi {$this->clientId}?!"
        ]);
    }

    public function endSessionError($data)
    {
        $this->dispatchBrowserEvent('swal:error', [
            'title' => 'Gagal!',
            'message' => "Sesi username {$data['user_session_username']} gagal diakhiri dari aplikasi {$this->clientId}?!"
        ]);
    }

    public function getUserSessionsProperty()
    {
        $params = [
            'first' => $this->first,
            'max' => $this->perPage,
        ];

        $user_sessions = (new KisaraClient(config('sso')))->userSessions($this->client_id, $params);

        return collect($user_sessions)->filter(function ($item) {
            $search = strtolower($this->search);
            return preg_match("/{$search}/", strtolower($item['username']));
        })->paginate($this->perPage);
    }

    public function render()
    {
        return view('livewire.client.user-sessions.table', [
            'user_sessions' => $this->user_sessions
        ]);
    }
}
