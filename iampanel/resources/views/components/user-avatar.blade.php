<div class="px-6 mt-6 mb-4 text-sm font-semibold">
    <div class="grid grid-cols-3">
        <x-user-picture class="mt-4"/>
        <div class="col-span-2 -ml-4">
            <p>{{ auth('imissu-web')->user()->family_name }}</p>
            <form class="text-gray-600 dark:text-gray-400 flex items-center">
                <select id="user-roles" onchange="changeRoleActive(this);" class="w-full lg:w-auto mt-1 shadow-sm border-gray-300 rounded-md text-sm focus-control dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700">
                    @php $role_active = auth('imissu-web')->user()->role_active @endphp
                    @foreach (auth('imissu-web')->user()->roles as $role)
                        <option value="{{ $role }}" {{ $role == $role_active ? 'selected' : '' }}>{{ $role }}</option>
                    @endforeach
                </select>
                <input type="hidden" name="url_change_role_active" value="{{ url('/web-session/change-role-active') }}">
                <input type="hidden" name="home_url" value="{{ route('home') }}">
            </form>
        </div>
    </div>
</div>