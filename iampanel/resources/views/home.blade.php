<x-app.layout>
    <div class="container px-6 mx-auto grid">

        <h1 class="my-6 text-3xl font-semibold text-gray-700 dark:text-gray-200">Halo, {{ auth('imissu-web')->user()->family_name }}</h1>

        <p class="dark:text-gray-200">
            Harap perbaharui data diri Anda di <a href="{{ route('user-profile') }}" class="underline text-blue-500 focus-visible-control">halaman Kelola Profil</a>.
        </p>

        @if (in_array(auth('imissu-web')->user()->role_active, array('Mahasiswa', 'Dosen', 'Pegawai')))
            <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">{{ __('Akses Aplikasi') }}</h2>

            <livewire:user-clients.table />
        @endif
    </div>
</x-app.layout>
