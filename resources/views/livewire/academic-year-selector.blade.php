<div class="flex items-center gap-3">
    {{-- <label for="academic-year-select" class="text-sm font-medium text-gray-950 dark:text-white">
        Academic Year:
    </label> --}}

    <div class="relative max-w-[250px]">
        <select id="academic-year-select" wire:model.live="selectedYearId"
            class="fi-input block w-full appearance-none rounded-lg shadow-sm border-0 py-2.5 pe-9 ps-3 text-sm transition duration-75 bg-white dark:bg-white/5 text-gray-950 dark:text-white ring-1 ring-inset ring-gray-950/10 dark:ring-white/20 focus:ring-2 focus:ring-inset focus:ring-primary-600 dark:focus:ring-primary-500">
            <option value="">All Years (No Filter)</option>
            @foreach ($academicYears as $year)
                <option value="{{ $year->id }}">
                    {{ $year->name }} ({{ $year->curriculum->name }})
                    @if ($year->is_active)
                        ✓
                    @endif
                </option>
            @endforeach
        </select>

        <div class="pointer-events-none absolute inset-y-0 end-0 flex items-center pe-3">
            <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                fill="currentColor">
                <path fill-rule="evenodd"
                    d="M10 3a.75.75 0 01.55.24l3.25 3.5a.75.75 0 11-1.1 1.02L10 4.852 7.3 7.76a.75.75 0 01-1.1-1.02l3.25-3.5A.75.75 0 0110 3zm-3.76 9.2a.75.75 0 011.06.04l2.7 2.908 2.7-2.908a.75.75 0 111.1 1.02l-3.25 3.5a.75.75 0 01-1.1 0l-3.25-3.5a.75.75 0 01.04-1.06z"
                    clip-rule="evenodd" />
            </svg>
        </div>
    </div>

    {{-- @if ($selectedYearId)
        <button
            wire:click="clearYear"
            type="button"
            class="fi-icon-btn fi-color-gray fi-icon-btn-size-md relative inline-flex items-center justify-center outline-none transition duration-75 focus-visible:ring-2 rounded-lg fi-icon-btn-size-md gap-1.5 px-2 py-1.5 text-sm shadow-sm bg-white dark:bg-white/5 ring-1 hover:bg-gray-50 dark:hover:bg-white/10 text-gray-950 dark:text-white ring-gray-950/10 dark:ring-white/20 focus-visible:ring-primary-600 dark:focus-visible:ring-primary-500"
            title="Clear selection"
        >
            <svg class="fi-icon-btn-icon h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    @endif --}}

</div>
