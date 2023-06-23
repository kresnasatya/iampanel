<?php

namespace App\Http\Livewire\Group;

use Livewire\Component;
use Livewire\WithPagination;
use RistekUSDI\Kisara\Group as KisaraGroup;

class Index extends Component
{
    use WithPagination;

    public $first = 0;
    public $perPage = 10;
    public $sortField = 'name';
    public $sortAsc = true;
    public $search = '';

    protected $listeners = ['refreshGroupsTable'];

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortAsc = !$this->sortAsc;
        } else {
            $this->sortAsc = true;
        }

        $this->sortField = $field;
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

    public function refreshGroupsTable()
    {
        $this->first = 0;
        $this->perPage = 10;
        $this->search = '';
        $this->sortField = 'name';
        $this->sortAsc = true;
    }

    /**
     * CATATAN
     * Fungsi hapus kelompok dihilangkan karena bila kelompok dihapus maka
     * isi anggota di dalamnya dihapus. Hal ini berdampak bila jumlah anggota
     * lebih dari 100 seperti faculty, staff, student, dll.
     */

    public function getGroupsProperty()
    {
        $params = [
            'first' => $this->first,
            'max' => $this->perPage,
        ];

        if (!empty($this->search)) {
            $params['search'] = $this->search;
        }

        $raw_groups = (new KisaraGroup(config('sso')))->get($params);

        return collect($raw_groups)->sortBy([
            [$this->sortField, $this->sortAsc ? 'asc': 'desc']
        ]);
    }

    public function render()
    {
        return view('livewire.group.index', [
            'groups' => $this->groups
        ]);
    }
}
