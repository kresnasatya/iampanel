<?php

namespace App\Http\Livewire\Client\Roles;

use Livewire\Component;
use Livewire\WithPagination;
use RistekUSDI\Kisara\ClientRole as KisaraClientRole;
use RistekUSDI\Kisara\Role as KisaraRole;

class Table extends Component
{
    use WithPagination;

    public $client_id;
    public $role_id;
    public $first = 0;
    public $perPage = 10;
    public $search = '';

    protected $listeners = ['refreshClientRolesTable', 'delete', 'deleteOk', 'deleteError'];

    public function mount($client_id)
    {
        $this->client_id = $client_id;
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

    public function refreshClientRolesTable()
    {
        $this->first = 0;
        $this->perPage = 10;
        $this->search = '';
    }

    public function deleteConfirm($role_id, $role_name)
    {
        $this->dispatchBrowserEvent('swal:confirm', [
            'title' => "Peringatan!",
            'message' => "Anda yakin akan menghapus peran {$role_name}?",
            'role_id' => $role_id,
            'role_name' => $role_name
        ]);
    }

    public function delete($role_id, $role_name)
    {
        $response = (new KisaraRole(config('sso')))->delete($role_id);

        if ($response['code'] === 204) {
            $this->emit('deleteOk', array(
                'role_name' => $role_name
            ));
        } else {
            $this->emit('deleteError', array(
                'role_name' => $role_name
            ));
        }
    }

    public function deleteOk($data)
    {
        $this->dispatchBrowserEvent('swal:ok', [
            'title' => 'Berhasil!',
            'message' => "Peran {$data['role_name']} berhasil dihapus!"
        ]);
    }

    public function deleteError($data)
    {
        $this->dispatchBrowserEvent('swal:error', [
            'title' => 'Gagal!',
            'message' => "Peran {$data['role_name']} gagal dihapus!"
        ]);
    }

    public function getClientRolesProperty()
    {
        $params = [
            'first' => $this->first,
            'max' => $this->perPage,
        ];

        if (!empty($this->search)) {
            $params['search'] = $this->search;
        }

        $client_roles = (new KisaraClientRole(config('sso')))->get($this->client_id, $params);

        return collect($client_roles);
    }

    public function render()
    {
        return view('livewire.client.roles.table', [
            'client_roles' => $this->client_roles,
        ]);
    }
}
