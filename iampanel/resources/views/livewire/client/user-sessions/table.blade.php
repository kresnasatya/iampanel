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
                window.livewire.emit('endSession', e.detail.user_session_id, e.detail.user_session_username);
            }
        });
    });

    window.addEventListener('swal:ok', (e) => {
        swal({
            title: e.detail.title,
            text: e.detail.message,
            icon: 'success',
        })
        .then(() => window.livewire.emit('refreshUserSessionsTable'));
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

<div>
    <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center mb-4">
        <div class="flex flex-col lg:flex-row space-y-2 lg:space-y-0 lg:space-x-2">
            <label>
                <select wire:model="perPage" class="w-1/2 lg:w-auto mt-1 shadow-sm border-gray-300 rounded-md text-sm focus:border-blue-400 focus:ring focus:ring-blue-400 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700">
                    <option>10</option>
                    <option>15</option>
                    <option>25</option>
                </select>
                <span class="text-gray-700 dark:text-gray-400">Per halaman</span>
            </label>
            <label>
                <input type="text" wire:model.lazy="search" placeholder="Cari pengguna..." class="w-full lg:w-auto shadow-sm border-gray-300 rounded-md mt-1 text-sm focus:border-blue-400 focus:ring focus:ring-blue-400 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700">
            </label>
            <button wire:click="refreshUserSessionsTable" class="w-full lg:w-auto mt-1 w-48 py-2 px-4 rounded-md font-semibold text-white bg-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">Perbaharui data</button>
        </div>
        <div class="mt-4 lg:mt-0">
            <div class="relative inline-block text-left">
                <div>

                </div>
                <div>

                </div>
            </div>
        </div>
    </div>

    <div wire:loading.flex wire:target="refreshUserSessionsTable" class="justify-center my-2">
        <p class="text-blue-500 semibold tracking-wide">Memuat ulang data user sessions...</p>
    </div>

    <div class="w-full mb-8 overflow-hidden rounded-lg shadow-sm border dark:border-opacity-10">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-nowrap">
                <thead>
                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                        <th class="px-4 py-3">
                            Username
                        </th>
                        <th class="px-4 py-3">
                            Akses dimulai
                        </th>
                        <th class="px-4 py-3">
                            Akses terakhir
                        </th>
                        <th class="px-4 py-3">
                            IP Address
                        </th>
                        <th class="px-4 py-3">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                    @foreach ($user_sessions as $user_session)
                        <tr class="text-gray-700 dark:text-gray-400">
                            <td class="px-4 py-3 text-sm">{{ $user_session['username'] }}</td>
                            <td class="px-4 py-3 text-sm" x-data="{ start: @js($user_session['start']) }">
                                <span x-text="new Intl.DateTimeFormat('id', { day: 'numeric', month: 'numeric', year: 'numeric',
                                    hour: 'numeric', minute: 'numeric', second: 'numeric', timeZoneName: 'short'}).format(start)"></span>
                            </td>
                            <td class="px-4 py-3 text-sm" x-data="{ lastAccess: @js($user_session['lastAccess']) }">
                                <span x-text="new Intl.DateTimeFormat('id', { day: 'numeric', month: 'numeric', year: 'numeric',
                                    hour: 'numeric', minute: 'numeric', second: 'numeric', timeZoneName: 'short'}).format(lastAccess)"></span>
                            </td>
                            <td class="px-4 py-3 text-sm">{{ $user_session['ipAddress'] }}</td>
                            <td class="px-4 py-3 text-sm">
                                @if (auth('imissu-web')->user()->username !== $user_session['username'])
                                <button class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-red-600 rounded-lg dark:text-red-400"
                                    aria-label="Akhiri sesi"
                                    title="Akhiri sesi"
                                    wire:click="endSessionConfirm('{{ $user_session['id'] }}', '{{ $user_session['username'] }}')">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </button>
                                @endif
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
                @include('includes._simple_pagination', ['items' => $user_sessions->toArray()])
            </span>
        </div>
    </div>
</div>