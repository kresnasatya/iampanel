@push('style')
<link rel="stylesheet" href="/css/notyf.min.css">
@endpush
@push('script')
<script src="/js/notyf.min.js"></script>
<script>
    window.addEventListener('add:ok', (e) => {
        const notyf = new Notyf();
        notyf.success(`${e.detail.message}`);
    });

    window.addEventListener('add:failed', (e) => {
        const notyf = new Notyf();
        notyf.error(`${e.detail.message}`);
    });
</script>
@endpush

<div class="container px-6 mx-auto grid">
    <h1 class="mt-6 text-3xl font-semibold text-gray-700 dark:text-gray-200">
        Kelompok {{ ucwords($groupName) }}
    </h1>
    <h2 class="mb-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
        {{ __('Tambah Anggota') }}
    </h2>

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
                    <select wire:model="q" class="w-full lg:w-auto mt-1 shadow-sm border-gray-300 rounded-md text-sm focus-control dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700">
                        <option value="">Tipe pengguna</option>
                        @foreach (get_q_user_type_id() as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </label>
                <label>
                    <input type="text" wire:model.lazy="search" placeholder="Cari pengguna..." class="w-full lg:w-auto shadow-sm border-gray-300 rounded-md mt-1 text-sm focus-control dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700">
                </label>
                <button wire:click="refreshUsersTable" class="w-full lg:w-auto mt-1 w-48 py-2 px-4 rounded-md font-semibold text-white bg-blue-500 focus-control">Perbaharui data</button>
            </div>
        </div>

        <div wire:loading.flex wire:target="refreshUsersTable, q, search" class="justify-center my-2">
            <p class="text-blue-500 semibold tracking-wide">Memuat ulang data pengguna...</p>
        </div>

        <div class="w-full mb-8 overflow-hidden rounded-lg shadow-sm border dark:border-opacity-10">
            <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-nowrap">
                    <thead>
                        <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                            <th class="px-4 py-3">
                                <a href="#" wire:click.prevent="sortBy('firstName')" role="button">
                                    <div class="flex">
                                        NIM / NIP
                                        @include('includes._sort-icon', ['field' => 'firstName'])
                                    </div>
                                </a>
                            </th>
                            <th class="px-4 py-3">
                                <a href="#" wire:click.prevent="sortBy('lastName')" role="button">
                                    <div class="flex">
                                        Nama
                                        @include('includes._sort-icon', ['field' => 'lastName'])
                                    </div>
                                </a>
                            </th>
                            <th class="px-4 py-3">
                                <div class="flex">
                                    Username
                                </div>
                            </th>
                            <th class="px-4 py-3">
                                <div class="flex">
                                    Email
                                </div>
                            </th>
                            <th class="px-4 py-3">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                        @foreach ($users as $user)
                            <tr class="text-gray-700 dark:text-gray-400">
                                <td class="px-4 py-3 text-sm">{{ $user['firstName'] }}</td>
                                <td class="px-4 py-3 text-sm">{{ $user['lastName'] }}</td>
                                <td class="px-4 py-3 text-sm">{{ $user['username'] }}</td>
                                <td class="px-4 py-3 text-sm">{{ isset($user['email']) ? $user['email'] : '-' }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center space-x-4 text-sm">
                                        <button wire:key="user-profile-{{ $user['id'] }}" wire:click="addUserToGroup('{{ $user['id'] }}', '{{ $user['username'] }}', '{{ $user['lastName'] }}')" class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-blue-600 rounded-lg dark:text-gray-400 focus-control">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="grid px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t dark:border-gray-700 bg-gray-50 sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800">
                <span class="flex items-center col-span-3">

                </span>
                <span class="col-span-2"></span>
                <span class="flex col-span-4 mt-2 sm:mt-auto sm:justify-end">
                    @include('includes._simple_pagination', ['items' => $users->toArray()])
                </span>
            </div>
        </div>
    </div>
</div>
