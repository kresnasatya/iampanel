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

    window.addEventListener('copy:env', (e) => {
        if (!navigator.clipboard) {
            return;
        }
        navigator.clipboard.writeText(e.detail.text)
        .then(() => {
            const notyf = new Notyf();
            notyf.success(`Nilai environment berhasil disalin`);
        })
        .catch(() => {
            const notyf = new Notyf();
            notyf.error(`Nilai environment gagal disalin!`);
        })
    });
</script>
@endpush

<div class="container px-6 mx-auto grid">
    <div class="flex flex-wrap items-center justify-between">
        <h1 class="my-6 text-3xl font-semibold text-gray-700 dark:text-gray-200 break-all">{{ strtoupper($clientId) }}</h1>
        <span class="bg-blue-500 text-white text-sm rounded-md p-2">{{ $client['protocol'] }}</span>
    </div>

    <div class="w-full overflow-hidden" x-data="{
            activeTab: window.location.hash ? window.location.hash.substring(1) : 'settings',
            activeClasses: 'tab active'
        }">
        <ul class="flex flex-col md:flex-row flex-wrap list-none border-b-0 pl-0 mb-4" role="tablist">
            <li class="flex-grow" role="presentation">
                <a @click.prevent="activeTab = 'settings'; window.location.hash = 'settings';" href="#settings"
                    class="tab focus-visible-control" :class="activeTab === 'settings' && activeClasses" role="tab">Pengaturan</a>
            </li>
            <li class="flex-grow" role="presentation">
                <a @click.prevent="activeTab = 'roles'; window.location.hash = 'roles';" href="#roles"
                    class="tab focus-visible-control" :class="activeTab === 'roles' && activeClasses" role="tab">Peran</a>
            </li>
            @if ($client['protocol'] === 'saml')
            <li class="flex-grow" role="presentation">
                <a @click.prevent="activeTab = 'keys'; window.location.hash = 'keys';" href="#keys"
                    class="tab focus-visible-control" :class="activeTab === 'keys' && activeClasses" role="tab">Keys</a>
            </li>
            @endif
            @if ($client['protocol'] === 'openid-connect')
            <li class="flex-grow" role="presentation">
                <a @click.prevent="activeTab = 'environment'; window.location.hash = 'environment';" href="#environment"
                    class="tab focus-visible-control" :class="activeTab === 'environment' && activeClasses" role="tab">Environment</a>
            </li>
            @endif
            <li class="flex-grow" role="presentation">
                <a @click.prevent="activeTab = 'user-sessions'; window.location.hash = 'user-sessions';" href="#user-sessions"
                    class="tab focus-visible-control" :class="activeTab === 'user-sessions' && activeClasses" role="tab">Sesi Aktif Pengguna</a>
            </li>
        </ul>
        <div class="mt-4 tab-panels">
            <div x-cloak x-show="activeTab === 'settings'" class="tab-panel">
                <form wire:submit.prevent="submit" class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800 flex flex-row">
                    <div class="w-full">
                        <label class="block mt-4">
                            <span class="text-gray-700 dark:text-gray-400 @error('clientId') text-red-500 @enderror text-sm">Client ID</span>
                            <input type="text" wire:model.lazy="clientId"
                                class="@if(!$allow_edit_clientId) bg-gray-300 @endif block w-full mt-1 rounded-md shadow-sm text-sm border-gray-300 @error('clientId') border-red-500 @enderror dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus-control"
                                value="{{ $client['clientId'] }}"
                                {{ $allow_edit_clientId ? '' : 'disabled'}}>
                            @error('clientId')
                            <span class="flex items-center tracking-wide text-red-500 text-sm mt-2 ml-1">{{ $message }}</span>
                            @enderror
                        </label>
                        <div class="mt-4">
                            <label for="client-types" class="block">
                                <span class="text-gray-700 dark:text-gray-400 text-sm">Tipe</span>
                            </label>
                            @if ($client['clientId'] !== config('sso.client_id'))
                            <select id="client-types" wire:model.lazy="publicClient" class="w-full mt-1 shadow-sm border-gray-300 rounded-md text-sm focus:border-blue-400 focus:ring focus:ring-blue-400 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700">
                                <option value="true">Public</option>
                                <option value="false">Confidential</option>
                            </select>
                            @else
                            <input type="text" class="block w-full mt-1 rounded-md shadow-sm text-sm bg-gray-300 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus-control" value="Confidential" disabled>
                            @endif
                        </div>
                        <label class="block mt-4">
                            <span class="text-gray-700 dark:text-gray-400 text-sm">Root URL</span>
                            <input type="text" wire:model.lazy="rootUrl" class="block w-full mt-1 rounded-md shadow-sm text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus-control">
                        </label>
                        <label class="block mt-4">
                            <span class="text-gray-700 dark:text-gray-400 @error('name') text-red-500 @enderror text-sm">Nama</span>
                            <input type="text" wire:model.lazy="name" class="block w-full mt-1 rounded-md shadow-sm text-sm border-gray-300 @error('name') border-red-500 @enderror dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus-control">
                            @error('name')
                            <span class="flex items-center tracking-wide text-red-500 text-sm mt-2 ml-1">{{ $message }}</span>
                            @enderror
                        </label>
                        <label class="block mt-4">
                            <span class="text-gray-700 dark:text-gray-400 @error('information') text-red-500 @enderror text-sm">Informasi</span>
                            <input type="text" wire:model.lazy="information" class="block w-full mt-1 rounded-md shadow-sm text-sm border-gray-300 @error('information') border-red-500 @enderror dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus-control">
                            @error('information')
                            <span class="flex items-center tracking-wide text-red-500 text-sm mt-2 ml-1">{{ $message }}</span>
                            @enderror
                        </label>
                        <div class="mt-4">
                            <label for="categories" class="block">
                                <span class="text-gray-700 dark:text-gray-400 @error('categories') text-red-500 @enderror text-sm">Kategori</span>
                            </label>
                            <select wire:model.defer="categories" name="categories" id="categories" multiple class="w-full dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus-control">
                                @foreach (get_client_categories() as $category)
                                    <option value="{{ $category }}">{{ $category }}</option>
                                @endforeach
                            </select>
                            @error('categories')
                            <span class="flex items-center tracking-wide text-red-500 text-sm mt-2 ml-1">{{ $message }}</span>
                            @enderror
                        </div>
                        <label class="block mt-4">
                            <span class="text-gray-700 dark:text-gray-400 @error('redirectUris') text-red-500 @enderror text-sm">Redirect URI</span>
                            @error('redirectUris')
                            <span class="flex items-center tracking-wide text-red-500 text-sm mt-2 ml-1">{{ $message }}</span>
                            @enderror
                        </label>
                        @foreach ($redirectUris as $index => $value)
                        <div wire:key="redirect-uri-{{$index}}" class="relative">
                            <input wire:model.lazy="redirectUris.{{$index}}" class="block w-full px-4 pr-20 mt-1 py-2 border border-r-0 shadow-sm text-sm text-black rounded-none rounded-l-md rounded-r-md dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus-control">
                            <button type="button" wire:click.prevent="removeRedirectUri({{$index}})" class="absolute inset-y-0 right-0 px-4 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-500 border border-transparent rounded-r-md active:bg-blue-500 hover:bg-blue-700 focus:outline-none focus:ring focus:ring-blue-600 focus:ring-opacity-50">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 000 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                        @endforeach
                        <div class="relative">
                            <input wire:model.lazy="redirectUri" class="block w-full px-4 pr-20 mt-1 py-2 border border-r-0 shadow-sm text-sm text-black rounded-none rounded-l-md rounded-r-md dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus-control">
                            <button type="button" wire:click.prevent="addRedirectUri" class="absolute inset-y-0 right-0 px-4 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-500 border border-transparent rounded-r-md active:bg-blue-500 hover:bg-blue-700 focus:outline-none focus:ring focus:ring-blue-600 focus:ring-opacity-50">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                        @if ($client['protocol'] === 'saml')
                        <div class="mt-4">
                            <label for="name-id-format" class="block">
                                <span class="text-gray-700 dark:text-gray-400 text-sm">Name ID format</span>
                            </label>
                            <select id="name-id-format" wire:model.defer="nameIdFormat" class="w-full mt-1 shadow-sm border-gray-300 rounded-md text-sm focus:border-blue-400 focus:ring focus:ring-blue-400 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700">
                                <option value="username">username</option>
                                <option value="email">email</option>
                                <option value="transient">transient</option>
                                <option value="persistent">persistent</option>
                            </select>
                        </div>
                        <label class="block mt-4">
                            <span class="text-gray-700 dark:text-gray-400 text-sm">Assertion Consumer Service POST Binding URL</span>
                            <input type="text" wire:model.defer="assertionConsumerUrlPost" class="block w-full mt-1 rounded-md shadow-sm text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus-control">
                        </label>
                        <label class="block mt-4">
                            <span class="text-gray-700 dark:text-gray-400 text-sm">Logout Service POST Binding URL</span>
                            <input type="text" wire:model.defer="singleLogoutServiceUrlPost" class="block w-full mt-1 rounded-md shadow-sm text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus-control">
                        </label>
                        <label class="block mt-4">
                            <span class="text-gray-700 dark:text-gray-400 text-sm">Logout Service Redirect Binding URL</span>
                            <input type="text" wire:model.defer="singleLogoutServiceUrlRedirect" class="block w-full mt-1 rounded-md shadow-sm text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus-control">
                        </label>
                        @endif
                        <div class="block mt-4 space-x-2">
                            <button type="submit" class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-500 border border-transparent rounded-lg active:bg-blue-500 hover:bg-blue-700 focus-control">Simpan</button>
                            <a href="{{ url('/clients') }}" class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-gray-800 dark:bg-black border border-transparent hover:bg-black rounded-lg focus-control">Batal</a>
                        </div>
                    </div>
                </form>
            </div>
            <div x-cloak x-show="activeTab === 'roles'" class="tab-panel">
                <livewire:client.roles.table :client_id="$client['id']" />
            </div>
            @if ($client['protocol'] === 'saml')
            <div x-cloak x-show="activeTab === 'keys'" class="tab-panel">
                <livewire:client.keys.index :id="$client['id']" />
            </div>
            @endif
            @if ($client['protocol'] === 'openid-connect')
            <div x-cloak x-show="activeTab === 'environment'" class="tab-panel">
                <livewire:client.environment.index :id="$client['id']" :client-id="$client['clientId']" />
            </div>
            @endif
            <div x-cloak x-show="activeTab === 'user-sessions'" class="tab-panel">
                <livewire:client.user-sessions.table :client_id="$client['id']" :client-id="$client['clientId']" />
            </div>
        </div>
    </div>
</div>
