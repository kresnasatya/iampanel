<div class="container px-6 mx-auto grid">
    <div class="flex flex-wrap items-center justify-between">
        <h1 class="my-6 text-3xl font-semibold text-gray-700 dark:text-gray-200 break-all">{{ strtoupper($clientName) }}</h1>
        <span class="bg-blue-500 text-white text-sm rounded-md p-2">{{ $clientProtocol }}</span>
    </div>
    <h2 class="mb-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
        {{ __('Peran') }} {{ $roleName }}
    </h2>

    <div class="w-full overflow-hidden" x-data="{
            activeTab: window.location.hash ? window.location.hash.substring(1) : 'users',
            activeClasses: 'tab active'
        }">
        <ul class="flex flex-col md:flex-row flex-wrap list-none border-b-0 pl-0 mb-4" role="tablist">
            <li class="flex-grow" role="presentation">
                <a @click.prevent="activeTab = 'users'; window.location.hash = 'users';" href="#users"
                    class="tab focus-visible-control" :class="activeTab === 'users' && activeClasses" role="tab">Pengguna</a>
            </li>
            <li class="flex-grow" role="presentation">
                <a @click.prevent="activeTab = 'groups'; window.location.hash = 'groups';" href="#groups"
                class="tab focus-visible-control" :class="activeTab === 'groups' && activeClasses" role="tab">Kelompok</a>
            </li>
        </ul>
        <div class="tab-panels">
            <div x-cloak x-show="activeTab === 'users'" class="tab-panel">
                <livewire:client.roles.member.users :clientId="$clientId" :roleId="$roleId" />
            </div>
            <div x-cloak x-show="activeTab === 'groups'" class="tab-panel">
                <livewire:client.roles.member.groups :clientId="$clientId" :roleId="$roleId" />
            </div>
        </div>
    </div>
</div>
