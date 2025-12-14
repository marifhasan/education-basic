# Onboarding Gate Implementation

**Implemented:** December 14, 2025
**Project:** School Management System

---

## Overview

A **mandatory, dynamic onboarding gate** has been implemented to ensure the system is properly configured before allowing access to the Admissions module. This is NOT a one-time wizard—it's a continuous compliance check that validates system readiness both during initial setup and after any configuration changes.

---

## Components

### 1. Settings Storage (`app_settings` table)

A new key-value settings table stores application-wide configuration:

**Table:** `app_settings`
- `id` - Primary key
- `key` - Setting name (unique)
- `value` - Setting value (stored as text)
- `type` - Data type (string, boolean, integer, json)
- `timestamps`

**Default Settings:**
- `onboarding_completed` (boolean) - Whether setup wizard has been marked complete
- `default_advance_monthly_fee` (integer) - Number of months to collect upfront during admission

**Model:** `App\Models\AppSetting`
- Static methods: `get()`, `set()`, `has()`
- Cached for performance (1 hour TTL)
- Automatic type casting

---

### 2. OnboardingChecklist Service

**Location:** `app/Services/OnboardingChecklist.php`

**Purpose:** Validates system readiness for admissions dynamically

#### Validation Rules

The checklist validates 7 critical requirements:

1. **Curriculum** - At least one curriculum must exist
2. **Academic Year** - One academic year must be marked as active (not closed)
3. **Base Classes** - At least one class must exist
4. **Sections** - Each class must have at least one active section for the current academic year
5. **Fee Structures** - Each class must have at least one active fee structure for the current academic year
6. **Education Fee** - A fee item named "Education Fee" must exist and be active
7. **Admission Configuration** - The `default_advance_monthly_fee` setting must exist

#### Key Methods

```php
OnboardingChecklist::steps(): array
// Returns all steps with status, icons, routes, and details

OnboardingChecklist::isComplete(): bool
// Checks if ALL steps are complete

OnboardingChecklist::completionPercentage(): int
// Returns 0-100% completion

OnboardingChecklist::canAccessAdmission(): bool
// Returns true ONLY if:
//   - onboarding_completed == true (marked complete)
//   - isComplete() == true (all checks still pass)
```

#### Dynamic Behavior

The service automatically detects configuration changes:
- **New class added** → Checklist becomes incomplete (missing section/fee structure)
- **Class removed** → Checklist recalculates for remaining classes
- **Section/fee structure deleted** → Checklist becomes incomplete
- **"Education Fee" deactivated** → Checklist becomes incomplete

---

### 3. Setup Wizard Page

**Location:** `app/Filament/Pages/SetupWizard.php`
**Route:** `/admin/setup`

**Features:**
- Visual checklist with ✔/❌ indicators
- Progress bar showing completion percentage
- Color-coded progress (red < 50%, yellow 50-99%, green 100%)
- Direct links to relevant Filament resources for each step
- Configuration form for `default_advance_monthly_fee`
- "Save Configuration" button
- "Mark Setup Complete" button (only visible when all checks pass)
- "Reset Onboarding" button (only visible when already complete)

**UI Sections:**
1. **Progress Summary** - Percentage, status message
2. **Admission Configuration** - Form to set advance monthly fee
3. **Checklist Steps** - All 7 steps with status and action buttons
4. **Footer Note** - Explains dynamic validation behavior

**Navigation Badge:**
- Shows completion percentage (e.g., "75%")
- Color changes based on progress (danger/warning/success)

---

### 4. Admission Access Lock

**Implemented in:** `app/Filament/Resources/Admissions/AdmissionResource.php`

#### Navigation Lock

```php
public static function shouldRegisterNavigation(): bool
{
    return OnboardingChecklist::canAccessAdmission()
        && AcademicYearContext::hasSelectedYear();
}
```

Admissions appear in navigation ONLY when:
1. Onboarding is marked complete
2. All onboarding checks pass
3. An academic year is selected

#### URL Access Lock

**Middleware:** `app/Http/Middleware/EnsureOnboardingComplete.php`

Applied to all admission pages:
- `ListAdmissions`
- `CreateAdmission`
- `EditAdmission`
- `ViewAdmission`

**Behavior:**
- Checks `OnboardingChecklist::canAccessAdmission()`
- Checks `AcademicYearContext::hasSelectedYear()`
- If either fails → Redirect to Setup Wizard with warning notification
- Notifications persist until dismissed

---

## Files Created

1. **Migration:** `database/migrations/2025_12_14_000001_create_app_settings_table.php`
2. **Model:** `app/Models/AppSetting.php`
3. **Service:** `app/Services/OnboardingChecklist.php`
4. **Page:** `app/Filament/Pages/SetupWizard.php`
5. **View:** `resources/views/filament/pages/setup-wizard.blade.php`
6. **Middleware:** `app/Http/Middleware/EnsureOnboardingComplete.php`

## Files Modified

1. **Seeder:** `database/seeders/FeeItemSeeder.php` - Added "Education Fee"
2. **Resource:** `app/Filament/Resources/Admissions/AdmissionResource.php` - Added onboarding check
3. **Page:** `app/Filament/Resources/Admissions/Pages/ListAdmissions.php` - Added middleware
4. **Page:** `app/Filament/Resources/Admissions/Pages/CreateAdmission.php` - Added middleware
5. **Page:** `app/Filament/Resources/Admissions/Pages/EditAdmission.php` - Added middleware
6. **Page:** `app/Filament/Resources/Admissions/Pages/ViewAdmission.php` - Added middleware

---

## Usage Flow

### Initial Setup

1. Admin logs in → Sees "Setup Wizard" in navigation with completion percentage
2. Clicks "Setup Wizard" → Sees checklist with incomplete items
3. Clicks "Configure" buttons to navigate to relevant resources:
   - Create curricula
   - Set up academic years
   - Add classes
   - Create sections for each class
   - Define fee structures for each class
   - Verify "Education Fee" exists (seeded by default)
4. Sets "Default Advance Monthly Fee" in the configuration form
5. Clicks "Save Configuration"
6. When all steps show ✓ → "Mark Setup Complete" button appears
7. Clicks "Mark Setup Complete" → Admissions module unlocks

### After Configuration Changes

**Scenario:** Admin adds a new class

1. New class is created
2. Onboarding checklist automatically detects missing section/fee structure
3. `OnboardingChecklist::isComplete()` returns `false`
4. Admissions module immediately locks (disappears from navigation)
5. Setup Wizard badge changes to show incomplete percentage
6. Admin must:
   - Add section for new class
   - Add fee structure for new class
7. Checklist becomes complete again
8. Admissions module automatically unlocks

---

## Security & Data Integrity

### Prevents Invalid Admissions

Before this implementation, admissions could be created even if:
- No sections existed
- No fee structures were configured
- Required fee items were missing
- System configuration was incomplete

This could lead to:
- Orphaned admission records
- Fee calculation errors
- Roll number generation failures
- Data inconsistencies

### Dynamic Validation

Unlike a one-time wizard, this system:
- Validates on every admission access attempt
- Responds to configuration changes in real-time
- Prevents administrative mistakes
- Ensures long-term data consistency

---

## Technical Notes

### Why Two Flags?

1. **`onboarding_completed` (setting)** - Admin's explicit acknowledgment
2. **`OnboardingChecklist::isComplete()` (runtime check)** - Current system state

Both must be `true` for admission access. This ensures:
- Admin has reviewed and approved initial setup
- System remains valid after changes

### Caching Strategy

`AppSetting` values are cached for 1 hour to reduce database queries. Cache is invalidated on updates.

### Performance Impact

- Minimal - Checklist validation runs only when:
  - Accessing admissions (navigation or URL)
  - Viewing setup wizard
- Database queries are optimized with proper indexes
- Results could be cached if performance becomes an issue

---

## Benefits

1. **Data Integrity** - Prevents invalid admissions from being created
2. **User Guidance** - Clear checklist shows exactly what's needed
3. **Flexibility** - Not locked to initial setup; adapts to changes
4. **Safety** - Automatic locking when configuration becomes invalid
5. **Transparency** - Visual feedback on system readiness
6. **Maintainability** - Centralized validation logic
7. **Extensibility** - Easy to add new validation rules

---

## Future Enhancements (Optional)

- Add more granular permissions (e.g., allow viewing but not creating admissions)
- Email notifications when onboarding becomes incomplete
- Audit log for onboarding status changes
- Batch validation for performance optimization
- Custom validation rules per institution
- Integration with system health dashboard

---

**End of Document**
