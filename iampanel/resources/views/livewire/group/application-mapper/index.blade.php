<div class="container px-6 mx-auto grid">
    <h1 class="my-6 text-3xl font-semibold text-gray-700 dark:text-gray-200">{{ __('Kelompok') }} {{ ucwords($group_name) }}</h1>
    <h2 class="mb-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
        {{ __('Pemetaan Peran Aplikasi') }}
    </h2>

    <div class="w-full overflow-hidden">
        <livewire:group.application-mapper.table :group_id="$group_id" />
    </div>
</div>
