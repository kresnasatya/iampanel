<div wire:init="loadEnvironment">
    @if (!empty($client_secret) && $allow_refresh_client_secret)
    <label class="block mt-4">
        <span class="text-gray-700 dark:text-gray-400 text-sm">Client Secret</span>
    </label>
    <div class="relative">
        <input id="client-secret" wire:model.lazy="client_secret" class="cursor-default bg-gray-100 block w-full px-4 pr-20 mt-1 py-2 border border-r-0 shadow-sm text-sm text-black rounded-none rounded-l-md rounded-r-md dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:outline-none focus:ring focus:ring-blue-400" readonly>
        <button type="button" wire:click="refreshClientSecret" class="absolute inset-y-0 right-0 px-4 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-500 border border-transparent rounded-r-md active:bg-blue-500 hover:bg-blue-700 focus:outline-none focus:ring focus:ring-blue-600 focus:ring-opacity-50">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
            </svg>
        </button>
    </div>
    @endif
    <div class="relative bg-gray-600 dark:bg-gray-800 text-slate-100 dark:text-slate-300 mx-auto rounded-md my-4">
        <button type="button" wire:click="copyEnv" class="absolute top-4 right-4 cursor-pointer" title="Salin environment">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
        </svg>
        </button>
      <pre class="whitespace-pre-line overflow-x-auto">
        <code class="block px-4">
            {{ $env_keys }}
        </code>
    </pre>
</div>
</div>
