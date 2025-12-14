<?php

namespace App\Services;

use App\Models\AcademicYear;
use Illuminate\Support\Facades\Session;

class AcademicYearContext
{
    private const SESSION_KEY = 'selected_academic_year_id';

    /**
     * Get the currently selected academic year ID from session
     */
    public static function getSelectedYearId(): ?int
    {
        return Session::get(self::SESSION_KEY);
    }

    /**
     * Get the currently selected academic year model
     */
    public static function getSelectedYear(): ?AcademicYear
    {
        $yearId = self::getSelectedYearId();

        return $yearId ? AcademicYear::find($yearId) : null;
    }

    /**
     * Set the selected academic year
     */
    public static function setSelectedYear(?int $yearId): void
    {
        if ($yearId) {
            Session::put(self::SESSION_KEY, $yearId);
        } else {
            Session::forget(self::SESSION_KEY);
        }
    }

    /**
     * Clear the selected academic year
     */
    public static function clearSelectedYear(): void
    {
        Session::forget(self::SESSION_KEY);
    }

    /**
     * Check if an academic year is currently selected
     */
    public static function hasSelectedYear(): bool
    {
        return Session::has(self::SESSION_KEY);
    }
}
