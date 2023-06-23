<div class="py-4 text-gray-500 dark:text-gray-400">
    <a class="ml-6 text-lg font-bold text-gray-800 dark:text-gray-200" href="{{ route('home') }}">
        {{ config("app.name", "IAMPANEL") }}
    </a>
    <x-user-avatar/>
    <ul class="mt-6">
        <li class="relative px-6 py-3">
            {!! request()->routeIs('home') ? '<span class="pointer-menu-active" aria-hidden="true"></span>' : '' !!}
            <a class="{{ request()->routeIs('home') ? 'menu active' : 'menu' }} focus-visible-control" href="{{ route('home') }}">
                @include("components.icons.home")
                <span class="ml-4">Beranda</span>
            </a>
        </li>
    </ul>
    <ul>
        @php
            $role_menus = load_role_menus()
        @endphp
        @foreach ($role_menus as $role => $menus)
            @if ($role == auth('imissu-web')->user()->role_active)
                @foreach ($menus as $menu)
                    <li class="relative px-6 py-3">
                        {!! request()->routeIs($menu['route_name']."*") ? '<span class="pointer-menu-active" aria-hidden="true"></span>' : '' !!}
                        <a href="{{ ($menu['route'] != '#') ? route($menu['route']) : $menu['route'] }}" class="{{ ($menu['route_name'] != '#') ? (request()->routeIs($menu['route_name'].'*') ? 'menu active' : 'menu') : 'menu' }} focus-visible-control">
                            @include("components.icons.{$menu['icon']}")
                            <span class="ml-4">{{ $menu['name'] }}</span>
                        </a>
                    </li>
                @endforeach
            @endif
        @endforeach
    </ul>
</div>
