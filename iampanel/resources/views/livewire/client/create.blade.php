<div class="container px-6 mx-auto grid">
    <h1 class="my-6 text-3xl font-semibold text-gray-700 dark:text-gray-200">{{ __('Tambah Aplikasi') }}</h1>
    <form wire:submit.prevent="submit" class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800 flex flex-row">
        <div class="w-full">
            @error('errorMessage')
                <span class="flex items-center tracking-wide text-red-500 text-sm mt-2 ml-1">{{ $message }}</span>
            @enderror
            <label class="block mt-4">
                <span class="text-gray-700 dark:text-gray-400 @error('clientId') text-red-500 @enderror text-sm">Client ID</span>
                <input type="text" wire:model.lazy="clientId" class="block w-full mt-1 rounded-md shadow-sm text-sm border-gray-300 @error('clientId') border-red-500 @enderror dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:outline-none focus:ring focus:ring-blue-400" value="{{ old('clientId') }}">
                @error('clientId')
                <span class="flex items-center tracking-wide text-red-500 text-sm mt-2 ml-1">{{ $message }}</span>
                @enderror
            </label>
            <div class="mt-4">
                <label for="client-protocol" class="block">
                    <span class="text-gray-700 dark:text-gray-400 text-sm">Protokol</span>
                </label>
                <select id="client-protocol" wire:model.lazy="protocol" class="w-full mt-1 shadow-sm border-gray-300 rounded-md text-sm focus:border-blue-400 focus:ring focus:ring-blue-400 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700">
                    <option value="openid-connect">OpenID Connect</option>
                    <option value="saml">SAML</option>
                </select>
            </div>
            <div class="mt-4">
                <label for="client-types" class="block">
                    <span class="text-gray-700 dark:text-gray-400 text-sm">Tipe</span>
                </label>
                <select id="client-types" wire:model.lazy="publicClient" class="w-full mt-1 shadow-sm border-gray-300 rounded-md text-sm focus:border-blue-400 focus:ring focus:ring-blue-400 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700">
                    <option value="true">Public</option>
                    <option value="false">Confidential</option>
                </select>
            </div>
            <label class="block mt-4">
                <span class="text-gray-700 dark:text-gray-400 text-sm">Root URL</span>
                <input type="text" wire:model.lazy="rootUrl" class="block w-full mt-1 rounded-md shadow-sm text-sm border-gray-300 @error('rootUrl') border-red-500 @enderror dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:outline-none focus:ring focus:ring-blue-400">
            </label>
            <div class="block mt-4 space-x-2">
                <button type="submit" class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-500 border border-transparent rounded-lg active:bg-blue-500 hover:bg-blue-700 focus:outline-none focus:ring focus:ring-blue-400">Simpan</button>
                <a href="{{ url('/clients') }}" class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-gray-800 dark:bg-black border border-transparent hover:bg-black rounded-lg focus:outline-none focus:ring focus:ring-blue-400">Batal</a>
            </div>
        </div>
    </form>
</div>
