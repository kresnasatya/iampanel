<div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800 flex flex-row">
    <div class="w-full">
        <label class="block">
            <span class="text-gray-700 dark:text-gray-400 @error('user_type_id') text-red-500 @enderror text-sm">Aplikasi</span>
            <select class="block w-full mt-1 shadow-sm border-gray-300 rounded-md text-sm focus:outline-none focus:ring focus:ring-blue-400 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300" wire:model="selectedClient" name="client_id" id="clients">
                <option value="" selected>Aplikasi</option>
                @foreach ($clients as $client)
                    <option value="{{ $client['id'] }}">{{ $client['clientId'] }}</option>
                @endforeach
            </select>
        </label>

        <div wire:loading wire:target="selectedClient" class="w-full mt-4 px-4 py-3 leading-normal text-blue-700 bg-blue-100 rounded-lg" role="alert">
            <p>Mohon menunggu...</p>
        </div>

        @if (!empty($selectedClient))
        <div class="flex flex-col space-y-4 md:grid md:grid-cols-3 md:gap-4 md:space-y-0 mt-4">
            <form wire:submit.prevent="storeAssignedClientRoles">
                <div class="flex flex-col space-y-4">
                    <label class="block" for="available-roles">
                        <span class="text-gray-700 dark:text-gray-400 @error('selected_available_roles') text-red-500 @enderror text-sm">Available roles</span>
                    </label>
                    <select wire:model.defer="selected_available_roles" name="selected_available_roles" id="available-roles" multiple class="dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">
                        @foreach ($available_roles as $role)
                            <option value="{{ json_encode($role) }}">{{ $role['name'] }}</option>
                        @endforeach
                    </select>
                    @error('selected_available_roles')
                    <span class="flex items-center tracking-wide text-red-500 text-sm mt-2 ml-1">Wajib dipilih!</span>
                    @enderror
                    <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-600 border border-transparent rounded-lg active:bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring focus:ring-blue-400">Tambahkan yang dipilih >></button>
                </div>
            </form>
            <form wire:submit.prevent="deleteAssignedClientRoles">
                <div class="flex flex-col space-y-4">
                    <label class="block" for="assigned-roles">
                        <span class="text-gray-700 dark:text-gray-400 @error('selected_assigned_roles') text-red-500 @enderror text-sm">Assigned roles</span>
                    </label>
                    <select wire:model.defer="selected_assigned_roles" name="selected_assigned_roles" id="assigned-roles" multiple class="dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">
                        @foreach ($assigned_roles as $role)
                            <option value="{{ json_encode($role) }}">{{ $role['name'] }}</option>
                        @endforeach
                    </select>
                    @error('selected_assigned_roles')
                    <span class="flex items-center tracking-wide text-red-500 text-sm mt-2 ml-1">Wajib dipilih!</span>
                    @enderror
                    <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-600 border border-transparent rounded-lg active:bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring focus:ring-blue-400"><< Hapus yang dipilih</button>
                </div>
            </form>
            <div class="flex flex-col space-y-4">
                <label class="block" for="effective-roles">
                    <span class="text-gray-700 dark:text-gray-400 text-sm">Effective roles</span>
                </label>
                <select name="effective_roles" id="effective-roles" multiple disabled class="disabled:opacity-50">
                    @foreach ($effective_roles as $role)
                        <option value="{{ json_encode($role) }}">{{ $role['name'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        @endif
    </div>
</div>
