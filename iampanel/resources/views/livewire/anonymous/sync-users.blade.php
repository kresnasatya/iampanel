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
<div x-data="{ loading: @entangle('loading'), hidden: @entangle('hidden') }" class="container px-6 mx-auto grid">
    <h1 class="my-6 text-3xl font-semibold text-gray-700 dark:text-gray-200">{{ __('Sync Users') }}</h1>
    <div x-cloak x-show="!hidden" class="inline-flex flex-wrap gap-4 justify-center md:justify-start">
        <div class="border border-slate-400 w-60 h-60 p-4">
            <div class="flex flex-col items-center">
                <button wire:key="fetch-oldiam-keycloak-users" type="button" @click="loading = true; hidden = true; @this.call('fetchOldIAMAndKeycloakUsers')" wire:loading.attr="disabled" wire:target="fetchOldIAMAndKeycloakUsers" class="inline-flex items-center px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-500 border border-transparent rounded-lg active:bg-blue-500 hover:bg-blue-700 focus:outline-none focus:ring focus:ring-blue-400">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" wire:target="fetchOldIAMAndKeycloakUsers" wire:loading>
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    1. Fetch Old IAM and Keycloak Users
                </button>
            </div>
        </div>
        <div class="border border-slate-400 w-60 h-60 p-4">
            <div class="flex flex-col items-center">
                <button wire:key="fetch-users-candidate" type="button" @click="loading = true; hidden = true; @this.call('fetchUsersCandidate')" wire:loading.attr="disabled" wire:target="fetchUsersCandidate" class="inline-flex items-center px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-500 border border-transparent rounded-lg active:bg-blue-500 hover:bg-blue-700 focus:outline-none focus:ring focus:ring-blue-400">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" wire:target="fetchUsersCandidate" wire:loading>
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    2. Fetch Users Candidate
                </button>
            </div>
        </div>
        <div class="border border-slate-400 w-60 h-60 p-4">
            <div class="flex flex-col items-center">
                <button wire:key="store-users-candidate" type="button" @click="loading = true; hidden = true; @this.call('storeUsersCandidate')" wire:loading.attr="disabled" wire:target="storeUsersCandidate" class="inline-flex items-center px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-500 border border-transparent rounded-lg active:bg-blue-500 hover:bg-blue-700 focus:outline-none focus:ring focus:ring-blue-400">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" wire:target="storeUsersCandidate" wire:loading>
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    3. Store Users Candidate
                </button>
            </div>
        </div>
    </div>
    @if (!$batchId)
    <div x-cloak x-show="loading" class="relative pt-1" wire:key="loading-placeholder">
        <div class="overflow-hidden h-4 flex rounded bg-green-100">
            <div style="width: 0%" class="bg-green-500 transition-all"></div>
        </div>
        <div class="flex justify-end">0%</div>
    </div>
    @endif
    @if ($batchId)
        @if ($batchFinished || $batchCancelled)
        <div class="relative pt-1" wire:key="loading-end">
        @else
        <div class="relative pt-1" wire:key="loading-start" wire:poll="updateBatchProgress">
        @endif
            <div class="overflow-hidden h-4 flex rounded bg-green-100">
                <div style="width: {{ $batchProgress }}%" class="bg-green-500 transition-all"></div>
            </div>
            <div class="flex justify-end">{{ $batchProgress }}%</div>
        </div>
        @if ($batchCancelled)
            <div class="mt-4 flex justify-end">
                <p class="text-red-600">Failed!</p>
                <button wire:click="$emit('stopHidden')" class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-gray-800 dark:bg-black border border-transparent hover:bg-black rounded-lg focus:outline-none focus:ring focus:ring-blue-400">Return</button>
            </div>
        @elseif ($batchFinished)
            <div class="mt-4 flex justify-end">
                <p class="text-green-600">Finish!</p>
                <button wire:click="$emit('stopHidden')" class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-gray-800 dark:bg-black border border-transparent hover:bg-black rounded-lg focus:outline-none focus:ring focus:ring-blue-400">Return</button>
            </div>
        @endif
    @endif
</div>
