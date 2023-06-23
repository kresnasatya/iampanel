<div class="container px-6 mx-auto grid">
    <h1 class="my-6 text-3xl font-semibold text-gray-700 dark:text-gray-200">{{ __('Master Pengguna') }}</h1>

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
                        @foreach ($user_types as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </label>
                <label>
                    <input type="text" wire:model.lazy="search" placeholder="Cari pengguna..." class="w-full lg:w-auto shadow-sm border-gray-300 rounded-md mt-1 text-sm focus-control dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700">
                </label>
                <button wire:click="refreshUsersTable" class="w-full lg:w-auto mt-1 w-48 py-2 px-4 rounded-md font-semibold text-white bg-blue-500 focus-control">Perbaharui data</button>
            </div>
            <!-- <div class="mt-4 lg:mt-0"> -->
                <!-- <label> -->
                    <!-- <a href="{{-- route('users.create') --}}" class="mt-1 inline-block lg:inline w-full lg:w-48 py-2 px-4 rounded-md font-semibold text-white bg-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 text-center">Tambah Pengguna</a>    -->
                <!-- </label> -->
            <!-- </div> -->
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
                                        <a href="{{ route('users.view', $user['id']) }}" class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-blue-600 rounded-lg dark:text-gray-400 focus-control focus-visible-control" aria-label="Lihat Pengguna" title="Lihat Pengguna">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                            </svg>
                                        </a>
                                        <a title="Sesi aktif" href="{{ route('users.user-sessions', $user['id']) }}" class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-blue-600 rounded-lg dark:text-gray-400 focus-control" aria-label="Sesi aktif">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-13a.75.75 0 00-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 000-1.5h-3.25V5z" clip-rule="evenodd" />
                                            </svg>
                                        </a>
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
