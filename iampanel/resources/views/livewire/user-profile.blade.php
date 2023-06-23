@push('style')
<link rel="stylesheet" href="/css/notyf.min.css">
@endpush

@push('script')
<script src="/js/cleave.min.js"></script>
<script src="/js/cleave-phone.id.js"></script>
<script src="/js/notyf.min.js"></script>
<script>
    var inputMobileNumber = new Cleave('#mobile-number', {
        phone: true,
        phoneRegionCode: 'ID'
    });

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                document.getElementById('user-photo').setAttribute('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]); // convert to base64 string
        }
    }

    document.getElementById('photo-upload').onchange = function(e) {
        readURL(e.target);
    }

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
    <h1 class="my-6 text-3xl font-semibold text-gray-700 dark:text-gray-200">{{ __('Kelola Profil') }}</h1>
    <div class="w-full" x-data="{
            activeTab: window.location.hash ? window.location.hash.substring(1) : 'user-profile',
            activeClasses: 'tab active'
        }">
        <ul class="flex flex-col md:flex-row flex-wrap list-none border-b-0 pl-0 mb-4" role="tablist">
            <li class="flex-grow" role="presentation">
                <a @click.prevent="activeTab = 'user-profile'; window.location.hash = 'user-profile';" href="#user-profile" class="tab focus-visible-control" :class="activeTab === 'user-profile' && activeClasses" role="tab">Profil</a>
            </li>
            <li class="flex-grow" role="presentation">
                <a @click.prevent="activeTab = 'reset-credentials'; window.location.hash = 'reset-credentials';" href="#roles" class="tab focus-visible-control" :class="activeTab === 'reset-credentials' && activeClasses" role="tab">Atur Kata Sandi</a>
            </li>
            <li class="flex-grow" role="presentation">
                <a @click.prevent="activeTab = 'device-activity'; window.location.hash = 'device-activity';" href="#device-activity" class="tab focus-visible-control" :class="activeTab === 'device-activity' && activeClasses" role="tab">Aktivitas Perangkat</a>
            </li>
            <li class="flex-grow" role="presentation">
                <a @click.prevent="activeTab = 'user-sessions'; window.location.hash = 'user-sessions';" href="#user-sessions" class="tab focus-visible-control" :class="activeTab === 'user-sessions' && activeClasses" role="tab">Sesi Aktif Aplikasi</a>
            </li>
        </ul>
        <div class="mt-4 tab-panels">
            <div x-cloak x-show="activeTab === 'user-profile'">
                <form wire:submit.prevent="submit" class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800 flex flex-col">
                    <label class="block mt-4">
                        <div class="mt-1 flex flex-col items-center">
                            <div class="bg-gray-300 dark:bg-gray-600 p-1 rounded" wire:ignore>
                                <img src="{{ $picture }}" alt="User's photo" width="140" height="200" class="object-contain" id="user-photo">
                            </div>
                            <div class="flex flex-col items-center mt-2">
                                <label for="photo-upload" class="relative cursor-default border border-gray-200 dark:border-gray-600 @error('photo') border-red-500 @enderror px-4 py-2 bg-white dark:bg-gray-700 rounded-md font-medium text-blue-600 dark:text-gray-300 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                    <span>Unggah foto</span>
                                    <input type="file" id="photo-upload" wire:model="photo" class="sr-only" accept="image/jpeg">
                                </label>
                                <p class="text-sm text-gray-700 dark:text-gray-400">JPG maksimal 5MB</p>
                                @error('photo')
                                <span class="flex items-center tracking-wide text-red-500 text-sm mt-2 ml-1">
                                    {{ $message }}
                                </span>
                                @enderror
                                <div wire:loading.inline-flex wire:target="photo" class="items-center">
                                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span class="text-blue-500">Mengunggah foto...</span>
                                </div>
                            </div>
                        </div>
                    </label>
                    <div class="grid md:grid-cols-2 gap-4">
                        <!-- Nama -->
                        <label class="block mt-4">
                            <span class="text-gray-700 dark:text-gray-400 @error('user.lastName') text-red-500 @enderror text-sm">Nama</span>
                            <input type="text" wire:model.defer="user.lastName" class="block w-full mt-1 rounded-md shadow-sm text-sm border-gray-300 @error('user.lastName') border-red-500 @enderror dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:outline-none focus:ring focus:ring-blue-400" placeholder="Nama">
                            @error('user.lastName')
                            <span class="flex items-center tracking-wide text-red-500 text-sm mt-2 ml-1">
                                {{ $message }}
                            </span>
                            @enderror
                        </label>
                        <!-- Email -->
                        <label class="block mt-4">
                            <span class="text-gray-700 dark:text-gray-400 @error('user.email') text-red-500 @enderror text-sm">Email</span>
                            <input type="email" wire:model.defer="user.email" class="block w-full mt-1 rounded-md shadow-sm text-sm border-gray-300 @error('user.email') border-red-500 @enderror dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:outline-none focus:ring focus:ring-blue-400">
                            @error('user.email')
                            <span class="flex items-center tracking-wide text-red-500 text-sm mt-2 ml-1">
                                {{ $message }}
                            </span>
                            @enderror
                        </label>
                    </div>
                    <div class="grid md:grid-cols-2 gap-4">
                        <label class="block mt-4">
                            <span class="text-gray-700 dark:text-gray-400 text-sm">NIM / NIP</span>
                            <input type="text" wire:model.defer="user.firstName" readonly class="cursor-default opacity-50 block w-full mt-1 rounded-md shadow-sm text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:outline-none focus:ring focus:ring-blue-400">
                        </label>
                        <!-- Username -->
                        <label class="block md:mt-4">
                            <span class="text-gray-700 dark:text-gray-400 @error('user.username') text-red-500 @enderror text-sm">Username</span>
                            <input type="text" wire:model.defer="user.username" class="cursor-default opacity-50 block w-full mt-1 rounded-md shadow-sm text-sm border-gray-300 @error('user.username') border-red-500 @enderror dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:outline-none focus:ring focus:ring-blue-400" readonly>
                            @error('user.username')
                            <span class="flex items-center tracking-wide text-red-500 text-sm mt-2 ml-1">
                                {{ $message }}
                            </span>
                            @enderror
                        </label>
                    </div>
                    <div class="grid md:grid-cols-2 gap-4">
                        <!-- NIK -->
                        <label class="block mt-4">
                            <span class="text-gray-700 dark:text-gray-400 text-sm">NIK</span>
                            <input type="text" wire:model.defer="user.attributes.nik.0" class="block w-full mt-1 rounded-md shadow-sm text-sm border-gray-300 @error('user.attributes.nik.0') border-red-500 @enderror dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:outline-none focus:ring focus:ring-blue-400" placeholder="NIK">
                            @error('user.attributes.nik.0')
                            <span class="flex items-center tracking-wide text-red-500 text-sm mt-2 ml-1">
                                {{ $message }}
                            </span>
                            @enderror
                        </label>
                        <!-- Phone number -->
                        <label class="block mt-4">
                            <span class="text-gray-700 dark:text-gray-400 @error('user.attributes.phone_number.0') text-red-500 @enderror text-sm">Nomor Ponsel</span>
                            <input type="text" id="mobile-number" wire:model.defer="user.attributes.phone_number.0" class="block w-full mt-1 rounded-md shadow-sm text-sm border-gray-300 @error('user.attributes.phone_number.0') border-red-500 @enderror dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:outline-none focus:ring focus:ring-blue-400" placeholder="Nomor Telepon">
                            @error('user.attributes.phone_number.0')
                            <span class="flex items-center tracking-wide text-red-500 text-sm mt-2 ml-1">
                                {{ $message }}
                            </span>
                            @enderror
                        </label>
                    </div>
                    <!-- Alamat -->
                    <label class="block mt-4">
                        <span class="text-gray-700 dark:text-gray-400 @error('user.attributes.address.0') text-red-500 @enderror text-sm">Alamat</span>
                        <textarea wire:model.defer="user.attributes.address.0" rows="3" class="block w-full mt-1 rounded-md shadow-sm text-sm border-gray-300 @error('user.attributes.address.0') border-red-500 @enderror dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:outline-none focus:ring focus:ring-blue-400"></textarea>
                        @error('user.attributes.address.0')
                        <span class="flex items-center tracking-wide text-red-500 text-sm mt-2 ml-1">
                            {{ $message }}
                        </span>
                        @enderror
                    </label>
                    <!-- Jenis Kelamin -->
                    <div class="mt-4 text-sm">
                        <span class="text-gray-700 dark:text-gray-400 @error('user.attributes.gender.0') text-red-500 @enderror">Jenis Kelamin</span>
                        <div class="mt-2">
                            <label class="inline-flex items-center text-gray-700 dark:text-gray-400 @error('user.attributes.gender.0') text-red-500 @enderror">
                                <input type="radio" class="text-blue-600 form-radio focus:ring focus:ring-blue-400" wire:model.defer="user.attributes.gender.0" value="M">
                                <span class="ml-2">Laki-laki</span>
                            </label>
                            <label class="inline-flex items-center ml-6 text-gray-700 dark:text-gray-400 @error('user.attributes.gender.0') text-red-500 @enderror">
                                <input type="radio" class="text-blue-600 form-radio focus:ring focus:ring-blue-400" wire:model.defer="user.attributes.gender.0" value="F">
                                <span class="ml-2">Perempuan</span>
                            </label>
                        </div>
                        @error('user.attributes.gender.0')
                        <span class="flex items-center tracking-wide text-red-500 text-sm mt-2 ml-1">
                            {{ $message }}
                        </span>
                        @enderror
                    </div>
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
                        <button type="submit" class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-500 border border-transparent rounded-lg active:bg-blue-500 hover:bg-blue-700 focus:outline-none focus:ring focus:ring-blue-400" wire:loading.attr="disabled" wire:click="submit">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" wire:loading wire:target="submit">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
            <div x-cloak x-show="activeTab === 'reset-credentials'" class="tab-panel">
                <livewire:user-credentials />
            </div>
            <div x-cloak x-show="activeTab === 'device-activity'" class="tab-panel">
                <livewire:user-device-activity />
            </div>
            <div x-cloak x-show="activeTab === 'user-sessions'" class="tab-panel">
                <livewire:user-active-app-sessions />
            </div>
        </div>
    </div>
</div>
