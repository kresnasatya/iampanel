<div class="w-full overflow-hidden">
    <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center mb-4">
        <div class="flex flex-col lg:flex-row space-y-2 lg:space-y-0 lg:space-x-2">
            <label>
                <select wire:model="perPage" class="w-1/2 lg:w-auto mt-1 shadow-sm border-gray-300 rounded-md text-sm focus-control focus-visible-control dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700">
                    <option>10</option>
                    <option>15</option>
                    <option>25</option>
                </select>
                <span class="text-gray-700 dark:text-gray-400">Per halaman</span>
            </label>
            <label>
                <input type="text" wire:model.lazy="search" placeholder="Cari aplikasi..." class="w-full lg:w-auto shadow-sm border-gray-300 rounded-md mt-1 text-sm focus-control focus-visible-control dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700">
            </label>
            <button wire:click="refreshUserClientsTable" class="lg:w-auto mt-1 w-48 py-2 px-4 rounded-md font-semibold text-white bg-blue-500 focus-control focus-visible-control">Perbaharui data</button>
        </div>
    </div>

    <div wire:loading.flex wire:target="refreshUserClientsTable, search" class="justify-center my-2">
        <p class="text-blue-500 semibold tracking-wide">Memuat ulang data aplikasi...</p>
    </div>

    <div class="w-full mb-8 overflow-hidden rounded-lg shadow-sm border dark:border-opacity-10">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-nowrap">
                <thead>
                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                        <th class="px-4 py-3">
                            <div class="flex">
                                Aplikasi
                            </div>
                        </th>
                        <th class="px-4 py-3">
                            <div class="flex">
                                Informasi
                            </div>
                        </th>
                        <th class="px-4 py-3">
                            <div class="flex">
                                Kategori
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                    @foreach ($user_clients as $key => $user_client)
                        <tr class="text-gray-700 dark:text-gray-400">
                            <td class="px-4 py-3 text-sm">
                                <a href="{{ $user_client['rootUrl'] }}" target="_blank" rel="noopener noreferrer" class="underline text-blue-600 hover:text-blue-800 visited:text-blue-600 focus-visible-control">
                                    {{ !empty($user_client['name']) ? $user_client['name'] : $user_client['clientId'] }}
                                </a>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $user_client['information'] }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ implode(', ', $user_client['categories']) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="grid px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t dark:border-gray-700 bg-gray-50 sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800">
            <span class="flex items-center col-span-3">
                Menampilkan {{ $user_clients->firstItem() }} sampai {{ $user_clients->lastItem() }} dari {{ $user_clients->total() }} hasil
            </span>
            <span class="col-span-2"></span>
            <span class="flex col-span-4 mt-2 sm:mt-auto sm:justify-end">
                {{ $user_clients->links('includes._pagination') }}
            </span>
        </div>
    </div>
</div>
