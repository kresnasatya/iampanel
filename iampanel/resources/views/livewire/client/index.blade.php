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
                    window.livewire.emit('delete', e.detail.id, e.detail.clientId);
                }
            });
    });

    window.addEventListener('swal:ok', (e) => {
        swal({
                title: e.detail.title,
                text: e.detail.message,
                icon: 'success',
            })
            .then(() => window.livewire.emit('refreshClientsTable'));
    });

    window.addEventListener('swal:error', (e) => {
        swal({
            title: e.detail.title,
            text: e.detail.message,
            icon: 'error',
        });
    });

    window.addEventListener('copy:env', (e) => {
        if (!navigator.clipboard) {
            return;
        }
        navigator.clipboard.writeText(e.detail.text)
            .then(() => {
                const notyf = new Notyf();
                notyf.success(`Nilai environment berhasil disalin`);
            })
            .catch(() => {
                const notyf = new Notyf();
                notyf.error(`Nilai environment gagal disalin!`);
            })
    });
</script>
@endpush

<div class="container px-6 mx-auto grid">
    <h1 class="my-6 text-3xl font-semibold text-gray-700 dark:text-gray-200">{{ __('Master Aplikasi') }}</h1>

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
                    <input type="text" wire:model.lazy="search" placeholder="Cari aplikasi..." class="w-full lg:w-auto shadow-sm border-gray-300 rounded-md mt-1 text-sm focus-control dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700">
                </label>
                <button wire:click="refreshClientsTable" class="w-full lg:w-auto mt-1 w-48 py-2 px-4 rounded-md font-semibold text-white bg-blue-500 focus-control">Perbaharui data</button>
            </div>
            @if (auth('imissu-web')->user()->role_active === 'Developer')
            <div class="mt-4 lg:mt-0">
                <label>
                    <a href="{{ route('clients.create') }}" class="mt-1 inline-block lg:inline w-full lg:w-48 py-2 px-4 rounded-md font-semibold text-white bg-blue-500 focus-control text-center">Tambah Aplikasi</a>
                </label>
            </div>
            @endif
        </div>

        <div wire:loading.flex wire:target="refreshClientsTable" class="justify-center my-2">
            <p class="text-blue-500 semibold tracking-wide">Memuat ulang data aplikasi...</p>
        </div>

        <div class="w-full mb-8 overflow-hidden rounded-lg border dark:border-opacity-10">
            <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-nowrap">
                    <thead>
                        <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                            <th class="px-4 py-4">
                                <div class="flex">
                                    Client ID
                                </div>
                            </th>
                            <th class="px-4 py-4">
                                <div class="flex">
                                    Protokol
                                </div>
                            </th>
                            <th class="px-4 py-4">
                                <div class="flex">
                                    Tipe
                                </div>
                            </th>
                            <th class="px-4 py-4">
                                <div class="flex">
                                    Root URL
                                </div>
                            </th>
                            <th class="px-4 py-4">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                        @foreach ($clients as $client)
                        <tr class="text-gray-700 dark:text-gray-400">
                            <td class="p-4 text-sm">{{ $client['clientId'] }}</td>
                            <td class="p-4 text-sm">{{ $client['protocol'] }}</td>
                            <td class="p-4 text-sm">
                                {{ $client['publicClient'] ? __('Public') : __('Confidential') }}
                            </td>
                            <td class="p-4 text-sm">
                                <a href="{{ isset($client['rootUrl']) ? $client['rootUrl'] : '#' }}" target="_blank" rel="noopener noreferrer" class="underline text-blue-500 focus-visible-control">
                                    @if (isset($client['rootUrl']))
                                    @if (in_array($client['clientId'], $hidden_clients))
                                    {{ '#' }}
                                    @else
                                    {{ $client['rootUrl'] }}
                                    @endif
                                    @else
                                    {{ '#' }}
                                    @endif
                                </a>
                            </td>
                            <td class="p-4">
                                @if (!in_array($client['clientId'], $hidden_clients))
                                <div class="flex items-center space-x-4 text-sm">
                                    @if (auth('imissu-web')->user()->role_active === 'Developer')
                                    <a href="{{ route('clients.edit', $client['id']) }}" class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-blue-600 rounded-lg dark:text-gray-400 focus-control" aria-label="Edit">
                                        <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                        </svg>
                                    </a>
                                    @endif
                                    @if (auth('imissu-web')->user()->role_active === 'Developer')
                                    <button class="copy-env__btn flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-blue-600 rounded-lg dark:text-gray-400 focus-control" aria-label="Salin environment key" title="Salin environment key" wire:click="copyEnv('{{ $client['id'] }}', '{{ $client['clientId'] }}')">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                                        </svg>
                                    </button>
                                    @endif
                                    <a title="Daftar peran" href="{{ route('clients.roles', $client['id']) }}" class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-blue-600 rounded-lg dark:text-gray-400 focus-control" aria-label="Daftar peran">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                                        </svg>
                                    </a>
                                    <a title="Sesi aktif pengguna" href="{{ route('clients.user-sessions', $client['id']) }}" class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-blue-600 rounded-lg dark:text-gray-400 focus-control" aria-label="Sesi aktif pengguna">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-13a.75.75 0 00-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 000-1.5h-3.25V5z" clip-rule="evenodd" />
                                        </svg>
                                    </a>
                                </div>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="grid px-4 py-4 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t dark:border-gray-700 bg-gray-50 sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800">
                <span class="flex items-center col-span-3"></span>
                <span class="col-span-2"></span>
                <span class="flex col-span-4 mt-2 sm:mt-auto sm:justify-end">
                    @include('includes._simple_pagination', ['items' => $clients->toArray()])
                </span>
            </div>
        </div>
    </div>
</div>
