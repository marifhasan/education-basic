You are working inside an existing Laravel 12 + FilamentPHP v4 school management system.

The system is already production-ready (~97% complete) with:

* Academic year context (session-based)
* Base Class model (year-independent)
* Year-scoped Sections, Admissions, Fee Structures
* Default Curriculum, Fee Items, Discount Types
* AdmissionService, EnrollmentService, MonthlyFeeService
* Context-based navigation and query filtering

DO NOT refactor existing models, services, or resources.
DO NOT introduce a new AcademicClass model.
ONLY add a dynamic onboarding gate that validates system readiness.

---

## OBJECTIVE

Implement a MANDATORY, DYNAMIC ADMIN ONBOARDING GATE.

Admission must be accessible ONLY WHEN:

* Initial setup is complete
* AND the system remains valid after changes
  (e.g., new class added, class removed, fees changed)

Onboarding is NOT a one-time wizard.
It is a continuous compliance check.

---

## WHAT YOU MUST ADD

### 1️⃣ Settings Storage

Use an existing settings table or create a minimal one if missing.

Required keys:

* onboarding_completed (boolean, default: false)
* default_advance_monthly_fee (integer, default: 0)

Do NOT over-engineer.
Single-row or key-value is acceptable.

---

### 2️⃣ OnboardingChecklist Service

Create:
app/Services/OnboardingChecklist.php

Responsibilities:

* Validate whether the system is READY for admission
* Re-validate dynamically when data changes

Expose methods:

* steps(): array
* isComplete(): bool

---

## CHECKLIST RULES (CRITICAL)

The checklist MUST validate the CURRENT STATE, not history.

Rules:

1. Curriculum

   * At least one curriculum exists

2. Academic Year

   * One academic year is marked as current

3. Base Classes

   * At least one base class exists

4. Per-Class Validation (Dynamic)
   For the CURRENT academic year:

   * For EACH active class:

     * At least one section exists
     * At least one ACTIVE class fee structure exists

   NOTE:

   * (Class + Academic Year) represents an implicit Academic Class
   * Do NOT create a separate model for this

5. Payment Items

   * A fee item named "Education Fee" exists and is active

6. Admission Configuration

   * default_advance_monthly_fee exists in settings

If ANY rule fails:

* isComplete() MUST return false
* Admission MUST be locked immediately

---

### 3️⃣ Filament Setup Wizard Page

Create a Filament Page:

app/Filament/Pages/SetupWizard.php
Route: /admin/setup

Purpose:

* GUIDE the admin
* NOT create or edit data

UI Requirements:

* Checklist display (✔ / ❌)
* Each step includes a button linking to the existing Filament resource
* Show progress percentage
* Show warning if onboarding is incomplete

"Mark Setup Complete" button:

* Visible ONLY if all checklist rules pass
* Sets onboarding_completed = true

---

### 4️⃣ Admission Access Lock (HARD REQUIREMENT)

Admission access must be guarded by BOTH:

* onboarding_completed == true
* OnboardingChecklist::isComplete() == true

Implement:

* AdmissionResource::shouldRegisterNavigation()
* Middleware or policy to block direct URL access

Admission must ALSO be blocked if:

* No academic year is selected in session

On failure:

* Redirect to /admin/setup
* Show a warning message

---

### 5️⃣ Dynamic Behavior (MANDATORY)

The system MUST react to changes automatically:

* New class added:

  * Checklist becomes incomplete
  * Admission locks again

* Class removed or deactivated:

  * Checklist recalculates based on remaining classes

* Section or fee removed:

  * Admission locks again

Do NOT rely solely on onboarding_completed flag.
Always re-check checklist dynamically.

---

## IMPORTANT CONSTRAINTS

* DO NOT modify existing business logic
* DO NOT modify roll number generation
* DO NOT refactor academic year context
* DO NOT add new domain models
* Reuse existing Filament resources and routes
* Keep code minimal, explicit, and well-commented

---

## DELIVERABLES

* Settings storage (if missing)
* OnboardingChecklist service
* SetupWizard Filament page
* Admission navigation + route lock
* Clear inline comments explaining decisions

The final result must:

* Protect admission integrity
* Handle future changes safely
* Integrate cleanly with the existing system
