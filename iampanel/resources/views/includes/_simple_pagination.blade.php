<nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between">
    <div class="flex justify-between flex-1 sm:hidden">
        <span>
        @if (empty($first))
            <span class="capitalize relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-400 cursor-default leading-5 rounded-md">
                {!! __('pagination.previous') !!}
            </span>
        @else
            <button wire:click="previousPage" wire:loading.attr="disabled" class="capitalize relative inline-flex items-center px-4 py-2 text-sm font-medium dark:text-gray-400 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:ring focus:border-blue-300 transition ease-in-out duration-150 shadow">
                {!! __('pagination.previous') !!}
            </button>
        @endif
        </span>
        
        <span>
        @if (count($items) < $perPage)
            <span class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-400 cursor-default leading-5 rounded-md">
                {!! __('pagination.next') !!}
            </span>
        @else
            <button wire:click="nextPage" wire:loading.attr="disabled" class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:ring focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150 shadow">
                {!! __('pagination.next') !!}
            </button>
        @endif
        </span>
    </div>

    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
        <div>
            <span class="capitalize relative z-0 inline-flex">
                <span>
                {{-- Previous Page Link --}}
                @if (empty($first))
                    <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                        <span class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-400 cursor-default rounded-l-md leading-5" aria-hidden="true">
                            {!! __('pagination.previous') !!}
                        </span>
                    </span>
                @else
                    <button wire:click="previousPage" wire:loading.attr="disabled" class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 rounded-l-md leading-5 hover:text-gray-400 focus:z-10 focus:outline-none focus:ring focus:border-blue-300 active:bg-gray-100 active:text-gray-500 transition ease-in-out duration-150 shadow" aria-label="{{ __('pagination.previous') }}">
                        {!! __('pagination.previous') !!}
                    </button>
                @endif
                </span>

                <span>
                {{-- Next Page Link --}}
                @if (count($items) < $perPage)
                    <span class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-400 cursor-default leading-5 rounded-md">
                        {!! __('pagination.next') !!}
                    </span>
                @else
                    <button wire:click="nextPage" wire:loading.attr="disabled" class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:ring focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150 shadow">
                        {!! __('pagination.next') !!}
                    </button>
                @endif
                </span>
            </span>
        </div>
    </div>
</nav>