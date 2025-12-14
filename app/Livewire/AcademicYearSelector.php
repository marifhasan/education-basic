<?php

namespace App\Livewire;

use App\Models\AcademicYear;
use App\Services\AcademicYearContext;
use Livewire\Component;

class AcademicYearSelector extends Component
{
    public ?int $selectedYearId = null;

    public function mount()
    {
        $this->selectedYearId = AcademicYearContext::getSelectedYearId();
    }

    public function updatedSelectedYearId($value)
    {
        AcademicYearContext::setSelectedYear($value);

        // Dispatch browser event to refresh the page/navigation
        $this->dispatch('academic-year-changed');

        // Redirect to refresh the navigation
        return redirect()->to(request()->header('Referer') ?? '/admin');
    }

    public function clearYear()
    {
        $this->selectedYearId = null;
        AcademicYearContext::clearSelectedYear();

        // Redirect to refresh the navigation
        return redirect()->to(request()->header('Referer') ?? '/admin');
    }

    public function render()
    {
        $academicYears = AcademicYear::with('curriculum')
            ->orderBy('start_date', 'desc')
            ->get();

        return view('livewire.academic-year-selector', [
            'academicYears' => $academicYears,
        ]);
    }
}
