<div class="container px-6 mx-auto grid">
    <h1 class="my-6 text-3xl font-semibold text-gray-700 dark:text-gray-200">{{ __('Master Kelompok') }}</h1>

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
                    <input type="text" wire:model.lazy="search" placeholder="Cari kelompok..." class="w-full lg:w-auto shadow-sm border-gray-300 rounded-md mt-1 text-sm focus-control dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700">
                </label>
                <button wire:click="refreshGroupsTable" class="w-full lg:w-auto mt-1 w-48 py-2 px-4 rounded-md font-semibold text-white bg-blue-500 focus-control">Perbaharui data</button>
            </div>
            @if (auth('imissu-web')->user()->role_active === 'Developer')
            <div class="mt-4 lg:mt-0">
                <label>
                    <a href="{{ route('groups.create') }}" class="mt-1 inline-block lg:inline w-full lg:w-48 py-2 px-4 rounded-md font-semibold text-white bg-blue-500 focus-control text-center">Tambah Kelompok</a>
                </label>
            </div>
            @endif
        </div>

        <div wire:loading.flex wire:target="refreshGroupsTable" class="justify-center my-2">
            <p class="text-blue-500 semibold tracking-wide">Memuat ulang data kelompok...</p>
        </div>

        <div class="w-full mb-8 overflow-hidden rounded-lg border dark:border-opacity-10">
            <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-nowrap">
                    <thead>
                        <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                            <th class="px-4 py-3">
                                <a href="#" wire:click.prevent="sortBy('name')" role="button">
                                    <div class="flex">
                                        Nama
                                        @include('includes._sort-icon', ['field' => 'name'])
                                    </div>
                                </a>
                            </th>
                            <th class="px-4 py-3">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                        @foreach ($groups as $group)
                        <tr class="text-gray-700 dark:text-gray-400">
                            <td class="px-4 py-3 text-sm">{{ $group['name'] }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center space-x-4 text-sm">
                                    @if (auth('imissu-web')->user()->role_active === 'Developer')
                                    <a title="Edit kelompok" href="{{ route('groups.edit', $group['id']) }}" class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-blue-600 rounded-lg dark:text-gray-400 focus-control" aria-label="Detail kelompok">
                                        <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                        </svg>
                                    </a>
                                    @endif
                                    <a title="Lihat anggota kelompok" href="{{ route('groups.members', $group['id']) }}" class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-blue-600 rounded-lg dark:text-gray-400 focus-control" aria-label="Anggota kelompok">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                                        </svg>
                                    </a>
                                    <a title="Pemetaan akses peran aplikasi" href="{{ route('groups.application-mapper', $group['id']) }}" class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-blue-600 rounded-lg dark:text-gray-400 focus-control" aria-label="Pemetaan akses peran aplikasi">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                            <path fill-rule="evenodd" d="M5.5 3A2.5 2.5 0 003 5.5v2.879a2.5 2.5 0 00.732 1.767l6.5 6.5a2.5 2.5 0 003.536 0l2.878-2.878a2.5 2.5 0 000-3.536l-6.5-6.5A2.5 2.5 0 008.38 3H5.5zM6 7a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
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
                <span class="flex items-center col-span-3"></span>
                <span class="col-span-2"></span>
                <span class="flex col-span-4 mt-2 sm:mt-auto sm:justify-end">
                    @include('includes._simple_pagination', ['items' => $groups->toArray()])
                </span>
            </div>
        </div>
    </div>
</div>
