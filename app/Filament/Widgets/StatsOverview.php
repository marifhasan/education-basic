<?php

namespace App\Filament\Widgets;

use App\Enums\AdmissionStatus;
use App\Enums\StudentStatus;
use App\Models\Admission;
use App\Models\Family;
use App\Models\Section;
use App\Models\Student;
use App\Services\AcademicYearContext;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $selectedYearId = AcademicYearContext::getSelectedYearId();

        // Total Students (active)
        $totalStudentsQuery = Student::where('status', StudentStatus::ACTIVE);
        if ($selectedYearId) {
            $totalStudentsQuery->whereHas('studentAcademicRecords', function ($query) use ($selectedYearId) {
                $query->where('academic_year_id', $selectedYearId)
                    ->where('is_active', true);
            });
        }
        $totalStudents = $totalStudentsQuery->count();

        // Pending Admissions
        $pendingAdmissionsQuery = Admission::where('status', AdmissionStatus::PENDING);
        if ($selectedYearId) {
            $pendingAdmissionsQuery->where('academic_year_id', $selectedYearId);
        }
        $pendingAdmissions = $pendingAdmissionsQuery->count();

        // Total Families
        $totalFamilies = Family::count();

        // Sections (if year selected)
        $sectionsStats = null;
        if ($selectedYearId) {
            $sections = Section::where('academic_year_id', $selectedYearId)
                ->where('is_active', true)
                ->selectRaw('COUNT(*) as total, SUM(current_strength) as students, SUM(capacity) as capacity')
                ->first();

            $sectionsStats = Stat::make('Sections', $sections->total ?? 0)
                ->description($sections->students . ' / ' . $sections->capacity . ' students')
                ->descriptionIcon('heroicon-m-users')
                ->color($sections && $sections->capacity > 0 && ($sections->students / $sections->capacity) > 0.8 ? 'warning' : 'success');
        }

        $stats = [
            Stat::make('Total Students', $totalStudents)
                ->description($selectedYearId ? 'In selected academic year' : 'All active students')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('success')
                ->chart([7, 12, 15, 18, 22, 25, $totalStudents]),

            Stat::make('Pending Admissions', $pendingAdmissions)
                ->description($selectedYearId ? 'For selected year' : 'All pending')
                ->descriptionIcon('heroicon-m-clock')
                ->color($pendingAdmissions > 0 ? 'warning' : 'gray'),

            Stat::make('Total Families', $totalFamilies)
                ->description('Registered families')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info'),
        ];

        // Add sections stat if year is selected
        if ($sectionsStats) {
            $stats[] = $sectionsStats;
        }

        return $stats;
    }
}
