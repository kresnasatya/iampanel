@push('style')
<link rel="stylesheet" href="/css/notyf.min.css">
@endpush
@push('script')
<script src="{{ asset('js/sweetalert.min.js') }}"></script>
<script src="/js/notyf.min.js"></script>
<script>
    window.addEventListener('delete-member:confirm', (e) => {
        swal({
                title: e.detail.title,
                text: e.detail.message,
                icon: 'warning',
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    window.livewire.emit('deleteMemberFromGroup', e.detail.member_id, e.detail.member_username, e.detail.member_name);
                }
            });
    });

    window.addEventListener('delete-member:ok', (e) => {
        const notyf = new Notyf();
        notyf.success(`${e.detail.message}`);
        window.livewire.emit('refreshMembersTable');
    });

    window.addEventListener('delete-member:failed', (e) => {
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
                <input type="text" wire:model.lazy="search" placeholder="Cari anggota..." class="w-full lg:w-auto shadow-sm border-gray-300 rounded-md mt-1 text-sm focus-control dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700">
            </label>
            <button wire:click="refreshMembersTable" class="w-full lg:w-auto mt-1 w-48 py-2 px-4 rounded-md font-semibold text-white bg-blue-500 focus-control">Perbaharui data</button>
        </div>
        <div class="mt-4 lg:mt-0">
            <label>
                <a href="{{ route('groups.add.members', $group_id) }}" class="mt-1 inline-block lg:inline w-full lg:w-48 py-2 px-4 rounded-md font-semibold text-white bg-blue-500 focus-control text-center">Tambah Anggota</a>
            </label>
        </div>
    </div>

    <div wire:loading.flex wire:target="refreshMembersTable, perPage, search" class="justify-center my-2">
        <p class="text-blue-500 semibold tracking-wide">Memuat ulang data anggota...</p>
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
                            <div class="flex">
                                Aksi
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                    @foreach ($members as $member)
                    <tr class="text-gray-700 dark:text-gray-400">
                        <td class="px-4 py-3 text-sm">{{ $member['firstName'] }}</td>
                        <td class="px-4 py-3 text-sm">{{ $member['lastName'] }}</td>
                        <td class="px-4 py-3 text-sm">{{ isset($member['username']) ? $member['username'] : '-' }}</td>
                        <td class="px-4 py-3 text-sm">{{ isset($member['email']) ? $member['email'] : '-' }}</td>
                        <td class="px-4 py-3 text-sm">
                            <button wire:key="member-profile-{{ $member['id'] }}" wire:click="deleteMemberConfirm('{{ $member['id'] }}', '{{ $member['username'] }}', '{{ $member['lastName'] }}')" class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-red-600 rounded-lg dark:text-gray-400 focus-red-control">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </button>
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
                @include('includes._simple_pagination', ['items' => $members->toArray()])
            </span>
        </div>
    </div>
</div>
