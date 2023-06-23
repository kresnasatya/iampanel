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

<div>
    <form wire:submit.prevent="submit" class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800 flex flex-row">
        @csrf
        <div class="w-full">
            <div class="flex flex-col space-y-4 md:grid md:grid-cols-2 md:gap-4" x-data="{ showPassword: false, showPasswordConfirmation: false }">
                <!-- Kata sandi -->
                <label class="block mt-4">
                    <span class="text-gray-700 dark:text-gray-400 @error('password') text-red-500 @enderror text-sm">Kata sandi baru</span>
                    <div class="relative">
                        <input :type="showPassword ? 'text' : 'password'" wire:model.defer="password" class="block w-full mt-1 rounded-md shadow-sm text-sm border-gray-300 @error('password') border-red-500 @enderror dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus-control" placeholder="Kata sandi baru">
                        <button type="button" @class(['absolute inset-y-0 right-0 px-4 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-500 border border-transparent rounded-r-md active:bg-blue-500 hover:bg-blue-700 focus-control']) @click="showPassword = !showPassword" :class="{ 'hidden': showPassword, 'block': !showPassword }">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </button>
                        <button type="button" @class(['absolute inset-y-0 right-0 px-4 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-500 border border-transparent rounded-r-md active:bg-blue-500 hover:bg-blue-700 focus-control']) @click="showPassword = !showPassword" :class="{ 'hidden': !showPassword, 'block': showPassword }">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                            </svg>
                        </button>
                    </div>
                    @error('password')
                    <span class="flex items-center tracking-wide text-red-500 text-sm mt-2 ml-1">{{ $message }}</span>
                    @enderror
                </label>
                <label class="block mt-4">
                    <span class="text-gray-700 dark:text-gray-400 @error('password') text-red-500 @enderror text-sm">Konfirmasi kata sandi baru</span>
                    <div class="relative">
                        <input :type="showPasswordConfirmation ? 'text' : 'password'" wire:model.defer="password_confirmation" class="block w-full mt-1 rounded-md shadow-sm text-sm border-gray-300 @error('password') border-red-500 @enderror dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus-control" placeholder="Konfirmasi kata sandi baru">
                        <button type="button" @class(['absolute inset-y-0 right-0 px-4 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-500 border border-transparent rounded-r-md active:bg-blue-500 hover:bg-blue-700 focus-control']) @click="showPasswordConfirmation = !showPasswordConfirmation" :class="{ 'hidden': showPasswordConfirmation, 'block': !showPasswordConfirmation }">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </button>
                        <button type="button" @class(['absolute inset-y-0 right-0 px-4 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-500 border border-transparent rounded-r-md active:bg-blue-500 hover:bg-blue-700 focus-control']) @click="showPasswordConfirmation = !showPasswordConfirmation" :class="{ 'hidden': !showPasswordConfirmation, 'block': showPasswordConfirmation }">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                            </svg>
                        </button>
                    </div>
                </label>
            </div>
            <div class="block mt-4 space-x-2">
                <button class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-500 border border-transparent rounded-lg active:bg-blue-500 hover:bg-blue-700 focus-control" wire:loading.attr="disabled" wire:click="submit">
                    <svg version="1.1" class="h-5 w-5" viewBox="0 0 100 100" enable-background="new 0 0 0 0" xml:space="preserve" wire:loading wire:target="submit">
                        <path fill="#fff" d="M73,50c0-12.7-10.3-23-23-23S27,37.3,27,50 M30.9,50c0-10.5,8.5-19.1,19.1-19.1S69.1,39.5,69.1,50">
                            <animateTransform attributeName="transform" attributeType="XML" type="rotate" dur="1s" from="0 50 50" to="360 50 50" repeatCount="indefinite" />
                        </path>
                    </svg>
                    Simpan
                </button>
            </div>
        </div>
    </form>
</div>
