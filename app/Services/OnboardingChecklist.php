<?php

namespace App\Services;

use App\Models\AcademicYear;
use App\Models\AppSetting;
use App\Models\ClassFeeStructure;
use App\Models\ClassModel;
use App\Models\Curriculum;
use App\Models\FeeItem;
use App\Models\Section;

/**
 * OnboardingChecklist Service
 *
 * Validates system readiness for admissions dynamically.
 * This is NOT a one-time wizard - it continuously checks compliance.
 */
class OnboardingChecklist
{
    /**
     * Get all onboarding steps with their current status
     *
     * @return array
     */
    public static function steps(): array
    {
        $currentYear = static::getCurrentAcademicYear();

        return [
            [
                'id' => 'curriculum',
                'title' => 'Create at least one curriculum',
                'description' => 'A curriculum defines the educational framework',
                'completed' => static::checkCurriculum(),
                'route' => 'filament.admin.resources.curricula.index',
                'icon' => 'heroicon-o-book-open',
            ],
            [
                'id' => 'academic_year',
                'title' => 'Set up an active academic year',
                'description' => 'One academic year must be marked as active',
                'completed' => static::checkAcademicYear(),
                'route' => 'filament.admin.resources.academic-years.index',
                'icon' => 'heroicon-o-calendar',
            ],
            [
                'id' => 'base_classes',
                'title' => 'Create at least one class',
                'description' => 'Base classes define grade levels (e.g., Class 1, Class 2)',
                'completed' => static::checkBaseClasses(),
                'route' => 'filament.admin.resources.class-models.index',
                'icon' => 'heroicon-o-academic-cap',
            ],
            [
                'id' => 'sections',
                'title' => 'Add sections for each class',
                'description' => $currentYear
                    ? 'Each class needs at least one section for ' . $currentYear->name
                    : 'Set up sections for the active academic year',
                'completed' => static::checkSections($currentYear),
                'route' => 'filament.admin.resources.sections.index',
                'icon' => 'heroicon-o-rectangle-group',
                'details' => static::getSectionDetails($currentYear),
            ],
            [
                'id' => 'fee_structures',
                'title' => 'Configure fee structures for each class',
                'description' => $currentYear
                    ? 'Each class needs at least one active fee structure for ' . $currentYear->name
                    : 'Set up fee structures for the active academic year',
                'completed' => static::checkFeeStructures($currentYear),
                'route' => 'filament.admin.resources.class-fee-structures.index',
                'icon' => 'heroicon-o-banknotes',
                'details' => static::getFeeStructureDetails($currentYear),
            ],
            [
                'id' => 'education_fee',
                'title' => 'Create "Education Fee" payment item',
                'description' => 'A fee item named "Education Fee" must exist and be active',
                'completed' => static::checkEducationFee(),
                'route' => 'filament.admin.resources.fee-items.index',
                'icon' => 'heroicon-o-currency-dollar',
            ],
            [
                'id' => 'admission_config',
                'title' => 'Set default advance monthly fee',
                'description' => 'Configure how many months to collect upfront during admission',
                'completed' => static::checkAdmissionConfig(),
                'route' => 'filament.pages.setup-wizard',
                'icon' => 'heroicon-o-cog-6-tooth',
            ],
        ];
    }

    /**
     * Check if ALL onboarding steps are complete
     *
     * @return bool
     */
    public static function isComplete(): bool
    {
        $steps = static::steps();

        foreach ($steps as $step) {
            if (!$step['completed']) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get completion percentage
     *
     * @return int
     */
    public static function completionPercentage(): int
    {
        $steps = static::steps();
        $totalSteps = count($steps);
        $completedSteps = count(array_filter($steps, fn($step) => $step['completed']));

        if ($totalSteps === 0) {
            return 0;
        }

        return (int) round(($completedSteps / $totalSteps) * 100);
    }

    /**
     * Get the current active academic year
     *
     * @return AcademicYear|null
     */
    protected static function getCurrentAcademicYear(): ?AcademicYear
    {
        return AcademicYear::where('is_active', true)
            ->where('is_closed', false)
            ->first();
    }

    /**
     * Rule 1: At least one curriculum exists
     */
    protected static function checkCurriculum(): bool
    {
        return Curriculum::count() > 0;
    }

    /**
     * Rule 2: One academic year is marked as current (active)
     */
    protected static function checkAcademicYear(): bool
    {
        return static::getCurrentAcademicYear() !== null;
    }

    /**
     * Rule 3: At least one base class exists
     */
    protected static function checkBaseClasses(): bool
    {
        return ClassModel::count() > 0;
    }

    /**
     * Rule 4a: For each active class, at least one section exists in current year
     */
    protected static function checkSections(?AcademicYear $currentYear): bool
    {
        if (!$currentYear) {
            return false;
        }

        $classes = ClassModel::all();

        if ($classes->isEmpty()) {
            return false;
        }

        // Each class must have at least one section for the current year
        foreach ($classes as $class) {
            $hasSection = Section::where('academic_year_id', $currentYear->id)
                ->where('class_id', $class->id)
                ->where('is_active', true)
                ->exists();

            if (!$hasSection) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get section details for display
     */
    protected static function getSectionDetails(?AcademicYear $currentYear): ?string
    {
        if (!$currentYear) {
            return 'No active academic year';
        }

        $classes = ClassModel::all();
        $missingClasses = [];

        foreach ($classes as $class) {
            $hasSection = Section::where('academic_year_id', $currentYear->id)
                ->where('class_id', $class->id)
                ->where('is_active', true)
                ->exists();

            if (!$hasSection) {
                $missingClasses[] = $class->name;
            }
        }

        if (empty($missingClasses)) {
            return '✓ All classes have sections';
        }

        return '✗ Missing sections for: ' . implode(', ', $missingClasses);
    }

    /**
     * Rule 4b: For each active class, at least one ACTIVE fee structure exists in current year
     */
    protected static function checkFeeStructures(?AcademicYear $currentYear): bool
    {
        if (!$currentYear) {
            return false;
        }

        $classes = ClassModel::all();

        if ($classes->isEmpty()) {
            return false;
        }

        // Each class must have at least one active fee structure for the current year
        foreach ($classes as $class) {
            $hasFeeStructure = ClassFeeStructure::where('academic_year_id', $currentYear->id)
                ->where('class_id', $class->id)
                ->where('is_active', true)
                ->exists();

            if (!$hasFeeStructure) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get fee structure details for display
     */
    protected static function getFeeStructureDetails(?AcademicYear $currentYear): ?string
    {
        if (!$currentYear) {
            return 'No active academic year';
        }

        $classes = ClassModel::all();
        $missingClasses = [];

        foreach ($classes as $class) {
            $hasFeeStructure = ClassFeeStructure::where('academic_year_id', $currentYear->id)
                ->where('class_id', $class->id)
                ->where('is_active', true)
                ->exists();

            if (!$hasFeeStructure) {
                $missingClasses[] = $class->name;
            }
        }

        if (empty($missingClasses)) {
            return '✓ All classes have fee structures';
        }

        return '✗ Missing fee structures for: ' . implode(', ', $missingClasses);
    }

    /**
     * Rule 5: A fee item named "Education Fee" exists and is active
     */
    protected static function checkEducationFee(): bool
    {
        return FeeItem::where('name', 'Education Fee')
            ->where('is_active', true)
            ->exists();
    }

    /**
     * Rule 6: default_advance_monthly_fee exists in settings
     */
    protected static function checkAdmissionConfig(): bool
    {
        return AppSetting::has('default_advance_monthly_fee');
    }

    /**
     * Check if onboarding is marked as completed in settings
     */
    public static function isMarkedComplete(): bool
    {
        return AppSetting::get('onboarding_completed', false);
    }

    /**
     * Mark onboarding as complete
     * Only callable if all steps are actually complete
     */
    public static function markComplete(): bool
    {
        if (!static::isComplete()) {
            return false;
        }

        AppSetting::set('onboarding_completed', true, 'boolean');
        return true;
    }

    /**
     * Check if admission is accessible
     * Requires BOTH onboarding complete AND all checks passing
     */
    public static function canAccessAdmission(): bool
    {
        return static::isMarkedComplete() && static::isComplete();
    }
}
