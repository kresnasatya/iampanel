<div class="container px-6 mx-auto grid">
    <div class="flex flex-wrap items-center justify-between">
        <h1 class="my-6 text-3xl font-semibold text-gray-700 dark:text-gray-200 break-all">{{ strtoupper($clientId) }}</h1>
        <span class="bg-blue-500 text-white text-sm rounded-md p-2">{{ $client_protocol }}</span>
    </div>
    <h2 class="mb-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
        {{ __('Sesi Aktif Pengguna') }}
    </h2>
    <div class="w-full overflow-hidden">
        <livewire:client.user-sessions.table :client_id="$client_id" :client-id="$clientId" />
    </div>
</div>