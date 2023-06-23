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
    <div class="flex flex-wrap items-center justify-between">
        <h1 class="my-6 text-3xl font-semibold text-gray-700 dark:text-gray-200 break-all">{{ strtoupper($client_name) }}</h1>
        <span class="bg-blue-500 text-white text-sm rounded-md p-2">{{ $client_protocol }}</span>
    </div>
    <h2 class="mb-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
        {{ __('Edit Peran') }} {{ $role['name'] }}
    </h2>

    <div class="w-full overflow-hidden">
        <form wire:submit.prevent="submit" class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800 flex flex-row">
            <div class="w-full">
                @error('errorMessage')
                    <span class="flex items-center tracking-wide text-red-500 text-sm mt-2 ml-1">{{ $message }}</span>
                @enderror
                <label class="block mt-4">
                    <span class="text-gray-700 dark:text-gray-400 @error('role.name') text-red-500 @enderror text-sm">Nama Peran</span>
                    <input type="text" wire:model.defer="role.name" class="block w-full mt-1 rounded-md shadow-sm text-sm border-gray-300 @error('client_id') border-red-500 @enderror dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus-control">
                    @error('role.name')
                    <span class="flex items-center tracking-wide text-red-500 text-sm mt-2 ml-1">{{ $message }}</span>
                    @enderror
                </label>
                <div class="block mt-4 space-x-2">
                    <button type="submit" class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-500 border border-transparent rounded-lg active:bg-blue-500 hover:bg-blue-700 focus-control">Simpan</button>
                    <a href="{{ url()->previous() }}" class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-gray-800 dark:bg-black border border-transparent hover:bg-black rounded-lg focus-control">Batal</a>
                </div>
            </div>
        </form>
    </div>
</div>
