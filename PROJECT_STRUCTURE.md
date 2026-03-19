# Project Structure

This project is a Laravel-based online exam system with the active application split by role and feature area.

## Active Runtime Areas

- `app/Http/Controllers/Admin`
  - Admin dashboard, exams, questions, results, analysis, and student management.
- `app/Http/Controllers/Student`
  - Student dashboard, exams, and result flows.
- `app/Http/Controllers/Auth`
  - Login, password reset, email verification, and password confirmation.
- `app/Http/Controllers/ProfileController.php`
  - Shared authenticated profile editing flow.

## View Structure

- `resources/views/admin`
  - Admin-facing pages.
- `resources/views/student`
  - Student-facing pages.
- `resources/views/profile`
  - Profile workspace and partial forms.
- `resources/views/auth`
  - Authentication screens.
- `resources/views/components`
  - Shared Blade components used by auth/profile/layout flows.
- `resources/views/layouts`
  - Shared layout wrappers.
- `resources/views/demo`
  - Public demo-test flow.
- `resources/views/welcome.blade.php`
  - Public landing page.

## Routes

- `routes/web.php`
  - Public landing page, profile routes, demo routes, and route-file loading.
- `routes/admin.php`
  - Admin-only routes.
- `routes/student.php`
  - Student-only routes.
- `routes/auth.php`
  - Authentication routes.

## Models

- `Exam`, `Question`, `Option`
  - Exam authoring structure.
- `Result`, `Response`
  - Student result and per-question response data.
- `ExamAttempt`
  - Still used in admin reporting/analysis code paths.
- `User`
  - Shared auth and profile model.

## Notes

- Student UI now lives fully under `resources/views/student/...`.
- Admin UI now lives fully under `resources/views/admin/...`.
- Old root-level legacy student/admin view files were removed where they were confirmed unused.
- One admin reporting branch still references `admin.exam-performance.*` views that are currently missing. That was left unchanged to avoid feature behavior changes during structure cleanup.
