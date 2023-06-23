@push('style')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css">
<link rel="stylesheet" href="/css/notyf.min.css">
@endpush

@push('script')
<script src="https://cdn.jsdelivr.net/npm/jquery@3.3.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script src="/js/notyf.min.js"></script>
<script>
    $(document).ready(function () {
        $('#application').select2();
        $('#application').on('change', function (e) {
            @this.selectedClient = e.target.value;

            Livewire.hook('message.sent', (message, component) => {
                console.log('component', component);
                window.dispatchEvent(
                    new CustomEvent('loading', { detail: { loading: true } })
                );
            });

            Livewire.hook('message.processed', () => {
                window.dispatchEvent(
                    new CustomEvent('loading', { detail: { loading: false } })
                );
            });
        });
    });

    window.addEventListener('toast:ok', (e) => {
        const notyf = new Notyf();
        notyf.success(e.detail.message);
    });

    window.addEventListener('toast:error', (e) => {
        const notyf = new Notyf();
        notyf.error(e.detail.message);
    });
</script>
@endpush

<div class="container px-6 mx-auto grid">
    <h1 class="my-6 text-3xl font-semibold text-gray-700 dark:text-gray-200">{{ __('Kelompok') }} {{ ucwords($group_name) }}</h1>
    <h2 class="mb-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
        {{ __('Tambah Akses Peran Aplikasi') }}
    </h2>
    <div class="my-4">
        <label for="application" class="block">
            <span class="text-gray-700 dark:text-gray-400 text-sm">Aplikasi</span>
        </label>
        <div wire:ignore>
            <select id="application" wire:model="selectedClient" class="block mt-1 shadow-sm border-gray-300 rounded-md text-sm focus-control dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700" style="width: 100%;">
                <option value="">Aplikasi</option>
                @foreach ($clients as $client)
                <option value="{{ $client['id'] }}">{{ $client['clientId'] }}</option>
                @endforeach
            </select>
        </div>
    </div>

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
                    <input type="text" wire:model.lazy="search" placeholder="Cari nama peran..." class="w-full lg:w-auto shadow-sm border-gray-300 rounded-md mt-1 text-sm focus-control dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700">
                </label>
                <button wire:click="refreshClientRolesTable" class="w-full lg:w-auto mt-1 w-48 py-2 px-4 rounded-md font-semibold text-white bg-blue-500 focus-control">Perbaharui data</button>
            </div>
        </div>

        <div x-data="{ loading: false }" x-show="loading" @loading.window="loading = $event.detail.loading" wire:loading.flex wire:target="selectedClient, refreshClientRolesTable, search" class="flex justify-center my-2">
            <p class="text-blue-500 semibold tracking-wide">Memuat ulang data...</p>
        </div>

        <div class="w-full mb-8 overflow-hidden rounded-lg shadow-sm border dark:border-opacity-10">
            <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-nowrap">
                    <thead>
                        <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                            <th class="px-4 py-3">
                                <div class="flex">
                                    Peran
                                </div>
                            </th>
                            <th class="px-4 py-3">
                                <div class="flex">
                                    Aksi
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                        @if (isset($available_client_roles))
                            @foreach ($available_client_roles as $client_role)
                            <tr class="text-gray-700 dark:text-gray-400">
                                <td class="px-4 py-3 text-sm">
                                    {{ $client_role ['name'] }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    <div class="flex items-center space-x-4 text-sm">
                                        <button title="Tambah akses peran aplikasi" wire:key="client-role-{{ $client_role['id'] }}" wire:click="assignRole('{{ json_encode($client_role) }}')" class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-blue-600 rounded-lg dark:text-gray-400 focus-control">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>

            <div class="grid px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t dark:border-gray-700 bg-gray-50 sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800">
                <span class="flex items-center col-span-3">
                    Menampilkan {{ $available_client_roles->firstItem() }} sampai {{ $available_client_roles->lastItem() }} dari {{ $available_client_roles->total() }} hasil
                </span>
                <span class="col-span-2"></span>
                <span class="flex col-span-4 mt-2 sm:mt-auto sm:justify-end">
                    {{ $available_client_roles->links('includes._pagination') }}
                </span>
            </div>
        </div>
    </div>
</div>
