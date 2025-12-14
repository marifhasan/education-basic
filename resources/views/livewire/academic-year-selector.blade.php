<div class="flex items-center gap-2">
    <label for="academic-year-select" class="text-sm font-medium text-gray-700 dark:text-gray-300">
        Academic Year:
    </label>

    <select
        id="academic-year-select"
        wire:model.live="selectedYearId"
        class="border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm py-1.5"
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
