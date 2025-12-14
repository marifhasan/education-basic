<div class="flex items-center gap-2">
    <label for="academic-year-select" class="text-sm font-medium text-gray-700 dark:text-gray-300">
        Academic Year:
    </label>

    <select
        id="academic-year-select"
        wire:model.live="selectedYearId"
        class="col-start-1 row-start-1 w-full appearance-none rounded-md py-1.5 pl-3 pr-8 text-base outline outline-1 -outline-offset-1 outline-gray-300 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-primary-600 sm:text-sm/6"
    >
        <option value="">All Years (No Filter)</option>
        @foreach($academicYears as $year)
            <option value="{{ $year->id }}">
                {{ $year->name }} ({{ $year->curriculum->name }})
                @if($year->is_active) ✓ @endif
            </option>
        @endforeach
    </select>

    @if($selectedYearId)
        <button
            wire:click="clearYear"
            type="button"
            class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
            title="Clear selection"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    @endif
</div>
