@push('style')
<link rel="stylesheet" href="/css/notyf.min.css">
@endpush
@push('script')
<script src="{{ asset('js/sweetalert.min.js') }}"></script>
<script src="/js/notyf.min.js"></script>
<script>
    window.addEventListener('delete-client-role:confirm', (e) => {
        swal({
            title: e.detail.title,
            text: e.detail.message,
            icon: 'warning',
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            console.log(e.detail.client_id, e.detail.role);
            if (willDelete) {
                window.livewire.emit('deleteAssignedClientRole', e.detail.client_id, e.detail.role);
            }
        });
    });

    window.addEventListener('delete-client-role:ok', (e) => {
        const notyf = new Notyf();
        notyf.success(`${e.detail.message}`);
        window.livewire.emit('refreshApplicationMapperTable');
    });

    window.addEventListener('delete-client-role:error', (e) => {
        const notyf = new Notyf();
        notyf.error(`${e.detail.message}`);
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
                <input type="text" wire:model.lazy="search" placeholder="Cari nama peran..." class="w-full lg:w-auto shadow-sm border-gray-300 rounded-md mt-1 text-sm focus-control dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700">
            </label>
            <button wire:click="refreshApplicationMapperTable" class="w-full lg:w-auto mt-1 w-48 py-2 px-4 rounded-md font-semibold text-white bg-blue-500 focus-control">Perbaharui data</button>
        </div>
        <div class="mt-4 lg:mt-0">
            <label>
                <a href="{{ route('users.assign-role', $user_id) }}" class="mt-1 inline-block lg:inline w-full lg:w-48 py-2 px-4 rounded-md font-semibold text-white bg-blue-500 focus-control text-center">Tambah Akses Peran</a>
            </label>
        </div>
    </div>

    <div wire:loading.flex wire:target="refreshApplicationMapperTable, search" class="justify-center my-2">
        <p class="text-blue-500 semibold tracking-wide">Memuat ulang data...</p>
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
                                Nama Peran
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
                    @foreach ($role_mappings as $key => $role_mapping)
                        <tr class="text-gray-700 dark:text-gray-400">
                            <td class="px-4 py-3 text-sm">
                                {{ $role_mapping['client'] }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $role_mapping['role_name'] }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                @if ($role_mapping['is_client_role'])
                                <button wire:key="role-{{ $role_mapping['role_id'] }}" wire:click="deleteAssignedClientRoleConfirm('{{ $role_mapping['client_id'] }}', '{{ json_encode($role_mapping) }}')" class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-red-600 rounded-lg dark:text-gray-400 focus-red-control">
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
            <span class="flex items-center col-span-3">
                Menampilkan {{ $role_mappings->firstItem() }} sampai {{ $role_mappings->lastItem() }} dari {{ $role_mappings->total() }} hasil
            </span>
            <span class="col-span-2"></span>
            <span class="flex col-span-4 mt-2 sm:mt-auto sm:justify-end">
                {{ $role_mappings->links('includes._pagination') }}
            </span>
        </div>
    </div>
</div>