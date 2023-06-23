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
            <fieldset class="block mt-4 border rounded p-4">
                <legend>
                    Signing keys config
                </legend>
                <label for="signing-keys-certificate" class="text-gray-700 dark:text-gray-400 text-sm">Certificate</label>
                <textarea wire:model.defer="samlSigningCertificate" id="signing-keys-certificate" rows="10" class="block w-full mt-1 rounded-md shadow-sm text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:outline-none focus:ring focus:ring-blue-400"></textarea>
            </fieldset>
            <fieldset class="block mt-4 border rounded p-4">
                <legend>
                    Encryption keys config
                </legend>
                <label for="encryption-keys-certificate" class="text-gray-700 dark:text-gray-400 text-sm">Certificate</label>
                <textarea wire:model.defer="samlEncryptionCertificate" id="encryption-keys-certificate" rows="10" class="block w-full mt-1 rounded-md shadow-sm text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:outline-none focus:ring focus:ring-blue-400"></textarea>
            </fieldset>
            <div class="block mt-4 space-x-2">
                <button class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-500 border border-transparent rounded-lg active:bg-blue-500 hover:bg-blue-700 focus:outline-none focus:ring focus:ring-blue-400" wire:loading.attr="disabled" wire:click="submit">
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
