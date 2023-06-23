<div class="container px-6 mx-auto grid">
    <div class="flex flex-wrap items-center justify-between">
        <h1 class="my-6 text-3xl font-semibold text-gray-700 dark:text-gray-200 break-all">{{ $user['firstName'] }} - {{ $user['lastName'] }}</h1>
        <span class="bg-blue-500 text-white text-sm rounded-md p-2">{{ $user_type }}</span>
    </div>

    <div class="w-full overflow-hidden" x-data="{
            activeTab: window.location.hash ? window.location.hash.substring(1) : 'settings',
            activeClasses: 'tab active'
        }">
        <ul class="flex flex-col md:flex-row flex-wrap list-none border-b-0 pl-0 mb-4" role="tablist">
            <li class="flex-grow" role="presentation">
                <a @click.prevent="activeTab = 'settings'; window.location.hash = 'settings';" href="#settings"
                    class="tab focus-visible-control" :class="activeTab === 'settings' && activeClasses" role="tab">Profil</a>
            </li>
            <li class="flex-grow" role="presentation">
                <a @click.prevent="activeTab = 'credentials'; window.location.hash = 'credentials';" href="#credentials"
                    class="tab focus-visible-control" :class="activeTab === 'credentials' && activeClasses" role="tab">Atur Kata Sandi</a>
            </li>
            <li class="flex-grow" role="presentation">
                <a @click.prevent="activeTab = 'user-sessions'; window.location.hash = 'user-sessions';" href="#user-sessions"
                    class="tab focus-visible-control" :class="activeTab === 'user-sessions' && activeClasses" role="tab">Sesi Aktif</a>
            </li>
            <li class="flex-grow" role="presentation">
                <a @click.prevent="activeTab = 'role-mappings'; window.location.hash = 'role-mappings';" href="#role-mappings"
                    class="tab focus-visible-control" :class="activeTab === 'role-mappings' && activeClasses" role="tab">Pemetaan Peran Aplikasi</a>
            </li>
        </ul>
        <div class="tab-panels">
            <div class="tab-panel" x-cloak x-show="activeTab === 'settings'">
                <livewire:user.edit :user="$user" />
            </div>
            <div class="tab-panel" x-cloak x-show="activeTab === 'credentials'">
                <livewire:user.credentials :user="$user" />
            </div>
            <div class="tab-panel" x-cloak x-show="activeTab === 'user-sessions'">
                <livewire:user.user-sessions.table :user="$user" />
            </div>
            <div class="tab-panel" x-cloak x-show="activeTab === 'role-mappings'">
                <livewire:user.application-mapper :user="$user" />
            </div>
        </div>
    </div>
</div>
