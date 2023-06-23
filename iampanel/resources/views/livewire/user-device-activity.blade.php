@push('style')
<link rel="stylesheet" href="/css/notyf.min.css">
@endpush

@push('script')
<script src="{{ asset('js/sweetalert.min.js') }}"></script>
<script src="/js/notyf.min.js"></script>
<script>
    window.addEventListener('swal-end-all-session:confirm', (e) => {
        swal({
            title: e.detail.title,
            text: e.detail.message,
            icon: 'warning',
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                window.livewire.emit('endAllSession');
            }
        });
    });

    window.addEventListener('swal:confirm', (e) => {
        swal({
            title: e.detail.title,
            text: e.detail.message,
            icon: 'warning',
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                window.livewire.emit('endSession', e.detail.session_id);
            }
        });
    });

    window.addEventListener('swal-end-session:ok', (e) => {
        swal({
            title: e.detail.title,
            text: e.detail.message,
            icon: 'success',
        });
    });

    window.addEventListener('swal-end-session:error', (e) => {
        swal({
            title: e.detail.title,
            text: e.detail.message,
            icon: 'error',
        });
    });

    window.addEventListener('swal-end-all-session:ok', (e) => {
        swal({
            title: e.detail.title,
            text: e.detail.message,
            icon: 'success',
        })
        .then(() => window.location.href = "/sso/logout");
    });
</script>
@endpush

<div>
    <div class="flex justify-between items-center mb-4">
        <div>
            <h2 class="text-2xl text-black dark:text-white">Aktivitas Perangkat</h2>
            <p class="text-sm text-black dark:text-white">Keluar dari perangkat yang tidak dikenal</p>
        </div>
        <div>
            <button wire:click="$refresh" class="lg:w-auto mt-1 w-48 py-2 px-4 rounded-md font-semibold text-white bg-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">Perbaharui</button>
            @if ($total_session > 1)
            <button wire:click="endAllSessionConfirm" class="lg:w-auto mt-1 w-48 py-2 px-4 rounded-md font-semibold text-white bg-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">Keluarkan Semua Perangkat</button>
            @endif
        </div>
    </div>

    <div wire:loading.flex wire:target="$refresh" class="justify-center my-2">
        <p class="text-blue-500 semibold tracking-wide">Memuat ulang aktivitas perangkat...</p>
    </div>

    @foreach($device_activity as $device)
    @php
    $count = count($device['sessions']) - 1;
    $sessions = $device['sessions'];
    @endphp
    @for ($i = $count; $i >= 0; $i--)
    <div class="border-t border-slate-300 flex justify-between items-center mb-4 p-4">
        <p class="text-black dark:text-white">{{ $device['os'] }} {{ $device['osVersion'] }} / {{ isset($sessions[$i]['browser']) ? $sessions[$i]['browser'] : '' }}
            @if (isset($sessions[$i]['current']))
            <span class="border rounded-md py-2 px-4 border-green-300 text-green-600 bg-green-100">Sesi terkini</span>
            @endif
        </p>
        @if (!isset($sessions[$i]['current']))
        <button wire:click="endSessionConfirm('{{ json_encode($sessions[$i]) }}', '{{ json_encode($device) }}')" class="lg:w-auto mt-1 w-48 py-2 px-4 rounded-md font-semibold text-white bg-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">Sign out</button>
        @endif
    </div>
    <table class="w-full mb-4">
        <thead>
            <tr class="text-xs font-semibold tracking-wide text-left text-black uppercase dark:text-white">
                <th class="px-4 py-3">IP Address</th>
                <th class="px-4 py-3">Akses Awal</th>
                <th class="px-4 py-3">Akses Terakhir</th>
                <th class="px-4 py-3">Akses Kadaluarsa</th>
                <th class="px-4 py-3">Aplikasi</th>
            </tr>
        </thead>
        <tbody x-data="{
                                started: @js($sessions[$i]['started']),
                                lastAccess: @js($sessions[$i]['lastAccess']),
                                expires: @js($sessions[$i]['expires'])
                            }">
            <tr class="text-black dark:text-white">
                <td class="px-4 py-3 text-sm">{{ $sessions[$i]['ipAddress'] }}</td>
                <td class="px-4 py-3 text-sm" x-text="new Intl.DateTimeFormat('id', { day: 'numeric', month: 'numeric', year: 'numeric',
                                hour: 'numeric', minute: 'numeric', second: 'numeric', timeZoneName: 'short'}).format(started * 1000)"></td>
                <td class="px-4 py-3 text-sm" x-text="new Intl.DateTimeFormat('id', { day: 'numeric', month: 'numeric', year: 'numeric',
                                hour: 'numeric', minute: 'numeric', second: 'numeric', timeZoneName: 'short'}).format(lastAccess * 1000)"></td>
                <td class="px-4 py-3 text-sm" x-text="new Intl.DateTimeFormat('id', { day: 'numeric', month: 'numeric', year: 'numeric',
                                hour: 'numeric', minute: 'numeric', second: 'numeric', timeZoneName: 'short'}).format(expires * 1000)"></td>
                <td class="px-4 py-3 text-sm w-40">
                    <span>{{ implode(',', array_column($sessions[$i]['clients'], 'clientId')) }}</span>
                </td>
            </tr>
        </tbody>
    </table>
    @endfor
    @endforeach
</div>
