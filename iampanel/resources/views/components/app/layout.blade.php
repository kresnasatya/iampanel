<!DOCTYPE html>
<html :class="{ 'dark': dark }" x-data="data()" lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config("app.name", "IAMPANEL") }}</title>
    @livewireStyles
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ mix('css/app.css') }}" />
    @stack('style')
    <script defer src="https://unpkg.com/alpinejs@3.10.2/dist/cdn.min.js"></script>
    <script src="{{ asset('js/init-alpine.js') }}"></script>
</head>

<body>
    <div class="flex h-screen bg-gray-50 dark:bg-gray-900" :class="{ 'overflow-hidden': isSideMenuOpen }">
        <x-app.desktop-sidebar />
        <x-app.mobile-sidebar />
        <div class="flex flex-col flex-1 w-full">
            <x-app.header />
            <main class="h-full overflow-y-auto">
                {{ $slot }}
            </main>
        </div>
    </div>
    @livewireScripts
    <script src="https://cdn.jsdelivr.net/npm/tw-elements/dist/js/index.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        function changeRoleActive(e) {
            const value = e.value;
            const url = document.querySelector('input[name="url_change_role_active"]').value;
            const home_url = document.querySelector('input[name="home_url"]').value;
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            if (value != '0') {
                fetch(url, {
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': token
                        },
                        method: 'POST',
                        credentials: 'same-origin',
                        body: `role_active=${value}`
                    })
                    .then(response => {
                        if (!response.ok) {
                            switch (response.status) {
                                case 401:
                                    throw new Error("Sesi login Anda sudah habis! Silakan login kembali.");
                                    break;
                                case 403:
                                    throw new Error("Tidak dapat mengubah peran aktif! Silakan login kembali.");
                                default:
                                    throw new Error("Terjadi kesalahan sistem! Silakan login kembali.");
                                    break;
                            }
                        } else {
                            return response.json();
                        }
                    })
                    .then(data => {
                        swal({
                            title: "",
                            text: data.message,
                            icon: "info"
                        })
                        .then(() => {
                            window.location.href = home_url;
                        });
                    })
                    .catch(err => {
                        swal({
                            title: "",
                            text: err.message,
                            icon: "error"
                        })
                        .then(() => {
                            window.location.reload();
                        });
                    });
            }
        }
    </script>
    @stack('script')
</body>

</html>