@push('style')
<link rel="stylesheet" href="/css/notyf.min.css">
@endpush

@push('script')
<script src="{{ asset('js/cleave.min.js') }}"></script>
<script src="{{ asset('js/cleave-phone.id.js') }}"></script>
<script src="/js/notyf.min.js"></script>
<script>
    var inputMobileNumber = new Cleave('#mobile-number', {
        phone: true,
        phoneRegionCode: 'ID'
    });

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

<form wire:submit.prevent="submit" class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800 flex flex-row">
    @csrf
    <div class="w-full">
        <div class="grid md:grid-cols-2 gap-4">
            <!-- Nama -->
            <label class="block mt-4">
                <span class="text-gray-700 dark:text-gray-400 @error('user.lastName') text-red-500 @enderror text-sm">Nama</span>
                <input type="text" wire:model.defer="user.lastName" class="block w-full mt-1 rounded-md shadow-sm text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 @error('user.lastName') border-red-500 @enderror focus-control" placeholder="Nama">
                @error('user.lastName')
                <span class="flex items-center tracking-wide text-red-500 text-sm mt-2 ml-1">{{ $message }}</span>
                @enderror
            </label>
            <!-- Email -->
            <label class="block mt-4">
                <span class="text-gray-700 dark:text-gray-400 @error('user.email') text-red-500 @enderror text-sm">Email</span>
                <input type="email" wire:model.defer="user.email" class="block w-full mt-1 rounded-md shadow-sm text-sm border-gray-300 @error('user.email') border-red-500 @enderror dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus-control" placeholder="Email">
                @error('user.email')
                <span class="flex items-center tracking-wide text-red-500 text-sm mt-2 ml-1">{{ $message }}</span>
                @enderror
            </label>
        </div>
        <div class="grid md:grid-cols-2 gap-4">
            <label class="block mt-4">
                <span class="text-gray-700 dark:text-gray-400 @error('user.firstName') text-red-500 @enderror text-sm">NIM / NIP</span>
                <input type="text" wire:model.defer="user.firstName" class="block w-full mt-1 rounded-md shadow-sm text-sm border-gray-300 @error('user.firstName') border-red-500 @enderror dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus-control" placeholder="NIM / NIP">
                @error('user.firstName')
                <span class="flex items-center tracking-wide text-red-500 text-sm mt-2 ml-1">{{ $message }}</span>
                @enderror
            </label>
            <!-- Username -->
            <label class="block mt-4">
                <span class="text-gray-700 dark:text-gray-400 text-sm">Username</span>
                <input type="text" wire:model.defer="user.username" class="opacity-50 block w-full mt-1 rounded-md shadow-sm text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus-control" placeholder="Username" disabled>
            </label>
        </div>
        <div class="grid md:grid-cols-2 gap-4">
            <!-- NIK -->
            <label class="block mt-4">
                <span class="text-gray-700 dark:text-gray-400 @error('user.attributes.nik.0') text-red-500 @enderror text-sm">NIK</span>
                <input type="text" wire:model.defer="user.attributes.nik.0" class="block w-full mt-1 rounded-md shadow-sm text-sm border-gray-300 @error('user.attributes.nik.0') border-red-500 @enderror dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus-control" placeholder="NIK">
                @error('user.attributes.nik.0')
                <span class="flex items-center tracking-wide text-red-500 text-sm mt-2 ml-1">{{ $message }}</span>
                @enderror
            </label>
            <label class="block mt-4">
                <span class="text-gray-700 dark:text-gray-400 @error('user.attributes.phone_number.0') text-red-500 @enderror text-sm">Nomor Ponsel</span>
                <input type="text" id="mobile-number" wire:model.defer="user.attributes.phone_number.0" class="block w-full mt-1 rounded-md shadow-sm text-sm border-gray-300 @error('user.attributes.phone_number.0') border-red-500 @enderror dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus-control" placeholder="Nomor Ponsel">
                @error('user.attributes.phone_number.0')
                <span class="flex items-center tracking-wide text-red-500 text-sm mt-2 ml-1">{{ $message }}</span>
                @enderror
            </label>
        </div>
        <!-- Alamat -->
        <label class="block mt-4">
            <span class="text-gray-700 dark:text-gray-400 @error('user.attributes.address.0') text-red-500 @enderror text-sm">Alamat</span>
            <textarea wire:model.defer="user.attributes.address.0" rows="3" class="block w-full mt-1 rounded-md shadow-sm text-sm border-gray-300 @error('user.attributes.address.0') border-red-500 @enderror dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus-control"></textarea>
            @error('user.attributes.address.0')
            <span class="flex items-center tracking-wide text-red-500 text-sm mt-2 ml-1">{{ $message }}</span>
            @enderror
        </label>
        <!-- Jenis Kelamin -->
        <div class="my-4">
            <span class="text-gray-700 dark:text-gray-400 @error('user.attributes.gender.0') text-red-500 @enderror text-sm">Jenis Kelamin</span>
            <div class="mt-2">
                <label class="inline-flex items-center text-gray-600 dark:text-gray-300 @error('user.attributes.gender.0') text-red-500 @enderror">
                    <input type="radio" class="text-blue-600 form-radio focus:ring focus:ring-blue-400" wire:model.defer="user.attributes.gender.0" value="M">
                    <span class="ml-2">Laki-laki</span>
                </label>
                <label class="inline-flex items-center ml-6 text-gray-600 dark:text-gray-300 @error('user.attributes.gender.0') text-red-500 @enderror">
                    <input type="radio" class="text-blue-600 form-radio focus:ring focus:ring-blue-400" wire:model.defer="user.attributes.gender.0" value="F">
                    <span class="ml-2">Perempuan</span>
                </label>
            </div>
            @error('user.attributes.gender.0')
            <span class="flex items-center tracking-wide text-red-500 text-sm mt-2 ml-1">{{ $message }}</span>
            @enderror
        </div>
        <label class="inline-block mt-4 text-sm">
            <input type="checkbox" wire:model.defer="user.enabled" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-offset-0 focus:ring-indigo-200 focus:ring-opacity-50">
            <span class="ml-2 text-gray-700 dark:text-gray-400">Aktifkan Pengguna</span>
        </label>
        <!-- Konfigurasi Autentikasi dua faktor -->
        <div class="mt-4 text-sm">
            <span class="text-gray-700 dark:text-gray-400">Konfigurasi Autentikasi dua faktor</span>
            <div class="mt-2 md:space-x-2">
                <label class="inline-flex items-center text-gray-700 dark:text-gray-400">
                    <input type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-offset-0 focus:ring-indigo-200 focus:ring-opacity-50" wire:model.defer="configureTOTP">
                    <span class="ml-2">One Time Password (OTP) Google Authenticator</span>
                </label>
                <label class="inline-flex items-center text-gray-700 dark:text-gray-400">
                    <input type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-offset-0 focus:ring-indigo-200 focus:ring-opacity-50" wire:model.defer="webAuthnRegisterPasswordless">
                    <span class="ml-2">Webauthn Register Passwordless</span>
                </label>
            </div>
        </div>
        <div class="block mt-4 space-x-2">
            <button type="submit" class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-500 border border-transparent rounded-lg active:bg-blue-500 hover:bg-blue-700 focus-control" wire:loading.attr="disabled" wire:click="submit">
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" wire:loading wire:target="submit">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Simpan
            </button>
            <a href="{{ url()->previous() }}" class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-gray-800 dark:bg-black border border-transparent hover:bg-black rounded-lg focus-control">Batal</a>
        </div>
    </div>
</form>
