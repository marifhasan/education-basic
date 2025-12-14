<?php

namespace App\Filament\Widgets;

use App\Models\ClassModel;
use App\Models\StudentAcademicRecord;
use App\Services\AcademicYearContext;
use Filament\Widgets\ChartWidget;

class StudentsByClassChart extends ChartWidget
{
    protected ?string $heading = 'Students Distribution by Class';

    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $selectedYearId = AcademicYearContext::getSelectedYearId();

        if (!$selectedYearId) {
            return [
                'datasets' => [
                    [
                        'label' => 'Students',
                        'data' => [],
                        'backgroundColor' => '#10b981',
                    ],
                ],
                'labels' => [],
            ];
        }

        // Get all classes with student count for selected year
        $classData = ClassModel::withCount([
            'studentAcademicRecords' => function ($query) use ($selectedYearId) {
                $query->where('academic_year_id', $selectedYearId)
                    ->where('is_active', true);
            },
        ])
            ->having('student_academic_records_count', '>', 0)
            ->orderBy('display_order')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Students',
                    'data' => $classData->pluck('student_academic_records_count')->toArray(),
                    'backgroundColor' => [
                        '#10b981', // green
                        '#3b82f6', // blue
                        '#f59e0b', // amber
                        '#ef4444', // red
                        '#8b5cf6', // purple
                        '#ec4899', // pink
                        '#14b8a6', // teal
                        '#f97316', // orange
                    ],
                ],
            ],
            'labels' => $classData->pluck('name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    public function getDescription(): ?string
    {
        $selectedYearId = AcademicYearContext::getSelectedYearId();

        return $selectedYearId
            ? 'Current enrollment distribution across classes'
            : 'Select an academic year to view distribution';
    }
}
