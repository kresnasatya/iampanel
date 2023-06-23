@push('style')
<link rel="stylesheet" href="/css/notyf.min.css">
@endpush
@push('script')
<script src="/js/notyf.min.js"></script>
<script>
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

    <div class="w-full overflow-hidden" x-data="{
            activeTab: window.location.hash ? window.location.hash.substring(1) : 'settings',
            activeClasses: 'tab active',
        }">
        <ul class="flex flex-col md:flex-row flex-wrap list-none border-b-0 pl-0 mb-4" role="tablist">
            <li class="flex-grow" role="presentation">
                <a @click.prevent="activeTab = 'settings'; window.location.hash = 'settings';" href="#settings" class="tab focus-visible-control" :class="activeTab === 'settings' && activeClasses" role="tab">Pengaturan</a>
            </li>
            <li class="flex-grow" role="presentation">
                <a @click.prevent="activeTab = 'members'; window.location.hash = 'members';" href="#members" class="tab focus-visible-control" :class="activeTab === 'members' && activeClasses" role="tab">Anggota</a>
            </li>
            <li class="flex-grow" role="presentation">
                <a @click.prevent="activeTab = 'role-mappings'; window.location.hash = 'role-mappings';" href="#role-mappings" class="tab focus-visible-control" :class="activeTab === 'role-mappings' && activeClasses" role="tab">Pemetaan Peran Aplikasi</a>
            </li>
        </ul>
        <div class="tab-panels">
            <div class="tab-panel" x-cloak x-show="activeTab === 'settings'">
                <form wire:submit.prevent="submit" class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800 flex flex-row">
                    <div class="w-full">
                        @error('errorMessage')
                        <span class="flex items-center tracking-wide text-red-500 text-sm mt-2 ml-1">{{ $message }}</span>
                        @enderror
                        <label class="block mt-4">
                            <span class="text-gray-700 dark:text-gray-400 @error('group_name') text-red-500 @enderror text-sm">Nama Kelompok</span>
                            <input type="text" wire:model.lazy="group_name" class="block w-full mt-1 rounded-md shadow-sm text-sm border-gray-300 @error('group_name') border-red-500 @enderror dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus-control">
                            @error('group_name')
                            <span class="flex items-center tracking-wide text-red-500 text-sm mt-2 ml-1">{{ $message }}</span>
                            @enderror
                        </label>
                        <div class="block mt-4 space-x-2">
                            <button type="submit" class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-500 border border-transparent rounded-lg active:bg-blue-500 hover:bg-blue-700 focus-control">Simpan</button>
                            <a href="{{ url('/groups') }}" class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-gray-800 dark:bg-black border border-transparent hover:bg-black rounded-lg focus-control">Batal</a>
                        </div>
                    </div>
                </form>
            </div>
            <div class="tab-panel" x-cloak x-show="activeTab === 'members'">
                <livewire:group.members.table :group_id="$group_id" :group_name="$group_name" />
            </div>
            <div class="tab-panel" x-cloak x-show="activeTab === 'role-mappings'">
                <livewire:group.application-mapper.table :group_id="$group_id" />
            </div>
        </div>
    </div>
</div>
