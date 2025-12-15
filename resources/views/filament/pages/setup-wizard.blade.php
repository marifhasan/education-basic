<x-filament-panels::page>
    {{-- Progress Section --}}
    <div class="mb-6">
        <div class="rounded-lg bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Setup Progress
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                            Complete all steps to unlock admissions
                        </p>
                    </div>
                    <div class="text-right">
                        <div class="text-3xl font-bold
                            @if($this->getCompletionPercentage() === 100) text-success-600
                            @elseif($this->getCompletionPercentage() >= 50) text-warning-600
                            @else text-danger-600
                            @endif">
                            {{ $this->getCompletionPercentage() }}%
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">Complete</div>
                    </div>
                </div>

                {{-- Progress Bar --}}
                <div class="w-full bg-gray-200 rounded-full h-3 dark:bg-gray-700">
                    <div class="h-3 rounded-full transition-all duration-500
                        @if($this->getCompletionPercentage() === 100) bg-success-600
                        @elseif($this->getCompletionPercentage() >= 50) bg-warning-600
                        @else bg-danger-600
                        @endif"
                        style="width: {{ $this->getCompletionPercentage() }}%">
                    </div>
                </div>

                {{-- Status Message --}}
                @if($this->isMarkedComplete() && $this->isAllStepsComplete())
                    <div class="mt-4 rounded-lg bg-success-50 p-4 dark:bg-success-500/10">
                        <div class="flex items-center">
                            <x-filament::icon
                                icon="heroicon-o-check-circle"
                                class="h-5 w-5 text-success-600 dark:text-success-400"
                            />
                            <p class="ml-3 text-sm font-medium text-success-800 dark:text-success-300">
                                Setup is complete! The system is ready for admissions.
                            </p>
                        </div>
                    </div>
                @elseif($this->isMarkedComplete() && !$this->isAllStepsComplete())
                    <div class="mt-4 rounded-lg bg-warning-50 p-4 dark:bg-warning-500/10">
                        <div class="flex items-center">
                            <x-filament::icon
                                icon="heroicon-o-exclamation-triangle"
                                class="h-5 w-5 text-warning-600 dark:text-warning-400"
                            />
                            <p class="ml-3 text-sm font-medium text-warning-800 dark:text-warning-300">
                                Warning: Setup was marked complete but some steps are now incomplete. Admissions are locked.
                            </p>
                        </div>
                    </div>
                @else
                    <div class="mt-4 rounded-lg bg-info-50 p-4 dark:bg-info-500/10">
                        <div class="flex items-center">
                            <x-filament::icon
                                icon="heroicon-o-information-circle"
                                class="h-5 w-5 text-info-600 dark:text-info-400"
                            />
                            <p class="ml-3 text-sm font-medium text-info-800 dark:text-info-300">
                                Complete all steps below, then click "Mark Setup Complete" to unlock admissions.
                            </p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- No configuration form needed - handled via Admission Fee Configurations resource --}}

    {{-- Checklist Steps --}}
    <div class="space-y-4">
        @foreach($this->getSteps() as $step)
            <div class="rounded-lg bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10
                @if($step['completed']) ring-2 ring-success-500 @endif">
                <div class="p-6">
                    <div class="flex items-start">
                        {{-- Icon and Status --}}
                        <div class="flex-shrink-0">
                            @if($step['completed'])
                                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-success-100 dark:bg-success-500/10">
                                    <x-filament::icon
                                        icon="heroicon-o-check-circle"
                                        class="h-6 w-6 text-success-600 dark:text-success-400"
                                    />
                                </div>
                            @else
                                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800">
                                    <x-filament::icon
                                        :icon="$step['icon']"
                                        class="h-6 w-6 text-gray-600 dark:text-gray-400"
                                    />
                                </div>
                            @endif
                        </div>

                        {{-- Content --}}
                        <div class="ml-4 flex-1">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h4 class="text-base font-semibold text-gray-900 dark:text-white">
                                        {{ $step['title'] }}
                                    </h4>
                                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                        {{ $step['description'] }}
                                    </p>
                                    @if(isset($step['details']))
                                        <p class="mt-2 text-sm font-medium
                                            @if($step['completed']) text-success-600 dark:text-success-400
                                            @else text-warning-600 dark:text-warning-400
                                            @endif">
                                            {{ $step['details'] }}
                                        </p>
                                    @endif
                                </div>

                                {{-- Action Button --}}
                                <div class="ml-4">
                                    @if($step['route'] !== 'filament.pages.setup-wizard')
                                        <a href="{{ route($step['route']) }}"
                                           class="inline-flex items-center rounded-lg px-3 py-2 text-sm font-medium
                                                  @if($step['completed'])
                                                      text-success-700 bg-success-50 hover:bg-success-100 dark:bg-success-500/10 dark:text-success-400 dark:hover:bg-success-500/20
                                                  @else
                                                      text-primary-700 bg-primary-50 hover:bg-primary-100 dark:bg-primary-500/10 dark:text-primary-400 dark:hover:bg-primary-500/20
                                                  @endif">
                                            @if($step['completed'])
                                                View
                                            @else
                                                Configure
                                            @endif
                                            <x-filament::icon
                                                icon="heroicon-m-arrow-right"
                                                class="ml-2 h-4 w-4"
                                            />
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Footer Note --}}
    <div class="mt-6 rounded-lg bg-gray-50 p-4 dark:bg-gray-800/50">
        <p class="text-sm text-gray-600 dark:text-gray-400">
            <strong>Note:</strong> This setup wizard validates the current state of your system.
            If you add new classes or modify configurations later, you may need to update sections
            and fee structures accordingly. The system will automatically lock admissions if required
            setup becomes incomplete.
        </p>
    </div>
</x-filament-panels::page>
