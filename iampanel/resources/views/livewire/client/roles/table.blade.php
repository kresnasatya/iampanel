@push('style')
<link rel="stylesheet" href="/css/notyf.min.css">
@endpush
@push('script')
<script src="{{ asset('js/sweetalert.min.js') }}"></script>
<script src="/js/notyf.min.js"></script>
<script>
    window.addEventListener('swal:confirm', (e) => {
        swal({
            title: e.detail.title,
            text: e.detail.message,
            icon: 'warning',
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                window.livewire.emit('delete', e.detail.role_id, e.detail.role_name);
            }
        });
    });

    window.addEventListener('swal:ok', (e) => {
        swal({
            title: e.detail.title,
            text: e.detail.message,
            icon: 'success',
        })
        .then(() => window.livewire.emit('refreshClientRolesTable'));
    });

    window.addEventListener('swal:error', (e) => {
        swal({
            title: e.detail.title,
            text: e.detail.message,
            icon: 'error',
        });
    });
</script>
@endpush

<div class="w-full overflow-hidden">
    <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center mb-4">
        <div class="flex flex-col lg:flex-row space-y-2 lg:space-y-0 lg:space-x-2">
            <label>
                <select wire:model="perPage" class="w-1/2 lg:w-auto mt-1 shadow-sm border-gray-300 rounded-md text-sm focus-control dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700">
                    <option>10</option>
                    <option>15</option>
                    <option>25</option>
                </select>
                <span class="text-gray-700 dark:text-gray-400">Per halaman</span>
            </label>
            <label>
                <input type="text" wire:model.lazy="search" placeholder="Cari peran..." class="w-full lg:w-auto shadow-sm border-gray-300 rounded-md mt-1 text-sm focus-control dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700">
            </label>
            <button wire:click="refreshClientRolesTable" class="w-full lg:w-auto mt-1 w-48 py-2 px-4 rounded-md font-semibold text-white bg-blue-500 focus-control">Perbaharui data</button>
        </div>
        @if (auth('imissu-web')->user()->role_active === 'Developer')
        <div class="mt-4 lg:mt-0">
            <div x-data="{ open: false }" class="relative inline-block text-left">
                <div>
                    <button @click="open = !open" type="button" class="inline-flex w-full justify-center rounded-md bg-blue-500 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-500 focus-control" id="menu-button" aria-expanded="true" aria-haspopup="true">
                    Tambah Peran
                    <!-- Heroicon name: mini/chevron-down -->
                    <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                    </svg>
                    </button>
                </div>
                <div
                    x-cloak
                    x-show="open"
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="transform opacity-100 scale-100"
                    x-transition:leave-end="transform opacity-0 scale-95"
                    class="absolute right-0 z-10 mt-2 w-56 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
                    <div class="py-1" role="none">
                    <!-- Active: "bg-gray-100 text-gray-900", Not Active: "text-gray-700" -->
                        <a href="{{ route('clients.roles.create', $client_id) }}" class="text-gray-700 hover:bg-gray-100 hover:text-gray-900 block px-4 py-2 text-sm focus-visible-control" role="menuitem" id="menu-item-0">Manual</a>
                        <a href="#" class="text-gray-700 hover:bg-gray-100 hover:text-gray-900 block px-4 py-2 text-sm focus-visible-control" role="menuitem" id="menu-item-1">Via API</a>
                        <a href="#" class="text-gray-700 hover:bg-gray-100 hover:text-gray-900 block px-4 py-2 text-sm focus-visible-control" role="menuitem" id="menu-item-1">Sync Users Client Role via API</a>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <div wire:loading.flex wire:target="refreshClientRolesTable" class="justify-center my-2">
        <p class="text-blue-500 semibold tracking-wide">Memuat ulang data peran...</p>
    </div>

    <div class="w-full mb-8 overflow-hidden rounded-lg shadow-sm border dark:border-opacity-10">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-nowrap">
                <thead>
                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                        <th class="px-4 py-3">
                            <div class="flex">
                                Nama
                            </div>
                        </th>
                        <th class="px-4 py-3">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                    @foreach ($client_roles as $role)
                        <tr class="text-gray-700 dark:text-gray-400">
                            <td class="px-4 py-3 text-sm">{{ $role['name'] }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center space-x-4 text-sm">
                                    @if (auth('imissu-web')->user()->role_active === 'Developer')
                                    <a href="{{ route('clients.roles.edit', [$client_id, $role['id']]) }}" class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-blue-600 rounded-lg dark:text-gray-400 focus-control" aria-label="Edit" title="Edit">
                                        <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                        </svg>
                                    </a>
                                    @endif
                                    <a href="{{ route('clients.roles.member', [$client_id, $role['id']]) }}" class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-blue-600 rounded-lg dark:text-gray-400 focus-control" aria-label="Lihat detail peran" title="Lihat detail peran">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                                        </svg>
                                    </a>
                                    @if (auth('imissu-web')->user()->role_active === 'Developer')
                                    <button class="delete__btn flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-red-600 rounded-lg dark:text-gray-400 focus-red-control"
                                        aria-label="Hapus peran {{ $role['name'] }}"
                                        title="Hapus peran {{ $role['name'] }}"
                                        wire:click="deleteConfirm('{{ $role['id'] }}', '{{ $role['name'] }}')">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="grid px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t dark:border-gray-700 bg-gray-50 sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800">
            <span class="flex items-center col-span-3"></span>
            <span class="col-span-2"></span>
            <span class="flex col-span-4 mt-2 sm:mt-auto sm:justify-end">
                @include('includes._simple_pagination', ['items' => $client_roles->toArray()])
            </span>
        </div>
    </div>
</div>
