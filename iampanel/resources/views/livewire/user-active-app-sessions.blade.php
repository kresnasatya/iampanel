<div>
    <div class="flex justify-between items-center mb-4">
        <div>
            <h2 class="text-2xl text-black dark:text-white">Sesi Aktif Aplikasi</h2>
        </div>
        <div>
            <button wire:click="$refresh" class="w-full lg:w-auto mt-1 w-48 py-2 px-4 rounded-md font-semibold text-white bg-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">Perbaharui</button>
        </div>
    </div>
    <div wire:loading.flex wire:target="$refresh" class="justify-center my-2">
        <p class="text-blue-500 semibold tracking-wide">Memuat ulang sesi aktif aplikasi...</p>
    </div>
    <div class="w-full mb-8 overflow-hidden rounded-lg shadow-sm border dark:border-opacity-10">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-nowrap">
                <thead>
                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                        <th class="px-4 py-3">
                            <div class="flex">
                                Nama Aplikasi
                            </div>
                        </th>
                        <th class="px-4 py-3">
                            <div class="flex">
                                Tautan Aplikasi
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                    @foreach ($active_app_sessions as $key => $app_session)
                        <tr class="text-gray-700 dark:text-gray-400">
                            <td class="px-4 py-3 text-sm">
                                {{ $app_session['clientId'] }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <a href="{{ !empty($app_session['rootUrl']) ? $app_session['rootUrl'] : '#' }}" target="_blank" rel="noopener noreferrer" class="underline text-blue-600 hover:text-blue-800 visited:text-blue-600">
                                    {{ !empty($app_session['rootUrl']) ? $app_session['rootUrl'] : '#' }}
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
