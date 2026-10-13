Solo Full-Stack Upskilling Plan: Working Hours Tracker

Product Goal
============

Build a small full-stack time tracking app for controlling your working hours. The app should let you enter start time, pauses, and optionally end time. It should calculate how much net work time you have completed and tell you when to stop working to reach an 8h target.

The app should also support days where you work less or more than 8h and make the balance visible.

Core domain model:
- WorkDay: date, targetMinutes, startTime, endTime, pauses, note, createdAt, updatedAt
- Pause: startTime, endTime, optional reason
- Derived values: workedMinutes, remainingMinutes, suggestedStopTime, overtimeMinutes, undertimeMinutes, status

Suggested stack:
- Backend: PHP + Symfony
- Frontend: React + TypeScript + Vite
- Styling: CSS Modules
- Database: MySQL
- Tests: PHPUnit, Vitest, React Testing Library, optional Playwright


Milestone 1: Backend Foundation
===============================

B1: Repository and Backend Skeleton
-----------------------------------

Create your GitHub repository with a simple structure:

time-tracker/
  backend/
  frontend/
  docs/
  README.md

Initialize Symfony in backend/, add basic Composer scripts, and document local setup.

Definition of Done:
- Backend starts locally.
- README explains setup and commands.
- No frontend or database complexity yet.

Learning focus:
GitHub workflow, Symfony project structure, Composer, local developer setup.


B2: CLI Time Tracker With File Storage
--------------------------------------

Build the backend domain through Symfony Console before HTTP or frontend.

Commands:
- workday:list
- workday:show <date>
- workday:start <date> <time>
- workday:add-pause <date> <start> <end>
- workday:finish <date> <time>
- workday:summary <date>

Behavior:
- Store data in a JSON file, for example var/data/workdays.json.
- Calculate net worked time from start, end, and pauses.
- If no end time is entered, calculate suggested stop time for the configured daily target.
- Default target is 8h, but each day can override it.

Definition of Done:
- You can create and inspect a workday from CLI.
- Data survives command restarts.
- Business logic lives in services, not directly in command classes.
- workday:summary can answer: "If I started at 08:30 and paused 45 minutes, when should I stop to reach 8h?"

Learning focus:
PHP types, Symfony Console, services, repositories, file persistence, date/time handling.


B3: Validation and Backend Tests
--------------------------------

Add validation before changing infrastructure.

Validation rules:
- Date must be valid.
- Start time must be before end time.
- Pause start must be before pause end.
- Pauses must not overlap.
- Pauses must be inside the workday if an end time exists.
- Target time must be positive and reasonable, for example 1h to 12h.

Add PHPUnit tests for:
- Work duration calculation.
- Suggested stop time calculation.
- Overtime and undertime calculation.
- Invalid overlapping pauses.
- File repository behavior.

Definition of Done:
- Invalid input returns understandable CLI errors.
- Main calculation rules are covered by tests.
- Edge cases around pauses are documented.

Learning focus:
Validation, testable services, date/time edge cases, PHPUnit.


B4: MySQL Persistence and HTTP API
----------------------------------

Replace file storage with MySQL and expose the behavior through HTTP.

Backend additions:
- Docker Compose with MySQL.
- Doctrine entities for WorkDay and Pause.
- Migrations.
- Repository implementation backed by MySQL.
- REST API endpoints:
  - GET /api/workdays
  - GET /api/workdays/{date}
  - POST /api/workdays
  - PATCH /api/workdays/{date}
  - POST /api/workdays/{date}/pauses
  - DELETE /api/workdays/{date}/pauses/{id}
  - GET /api/workdays/{date}/summary

Definition of Done:
- CLI and HTTP API share the same service logic.
- Workdays persist in MySQL.
- API returns structured validation errors.
- API contract is documented in docs/api.md.

Learning focus:
MySQL, Doctrine, migrations, controllers, DTOs, HTTP semantics.


Milestone 2: Frontend Foundation
================================

F1: Frontend Setup
------------------

Initialize React + TypeScript + Vite in frontend/.

Add:
- CSS Modules.
- Vitest.
- React Testing Library.
- Formatting/lint scripts.
- Basic layout with Home and About pages.

Definition of Done:
- Frontend runs without backend.
- README documents frontend commands.
- App has a simple shell layout.

Learning focus:
Vite, React, TypeScript, frontend tooling.


F2: Local Time Tracking UI
--------------------------

Build the UI with local useState data first.

Screens/components:
- Today dashboard.
- Workday form.
- Pause list.
- Summary card.
- Weekly overview.
- Empty state.

Core UI behavior:
- Enter start time.
- Add one or more pauses.
- Optionally enter end time.
- Show worked time.
- Show suggested stop time for 8h.
- Show overtime or undertime if the day is finished.

Definition of Done:
- Main flow works without backend.
- Calculation results are visible in the UI.
- UI handles unfinished and finished days.
- Component boundaries are understandable.

Learning focus:
React state, props, derived UI state, component design, TypeScript models.


F3: Routing and Forms
---------------------

Add real frontend routes and validated forms.

Routes:
- /
- /days/:date
- /days/:date/edit
- /about

Use React Hook Form and Zod for:
- Workday create/edit form.
- Pause form.
- Target hours override.

Definition of Done:
- Invalid form data shows field-level errors.
- Browser back/forward works.
- Direct navigation to a day route is supported locally or documented as limited until backend integration.
- At least one form test exists.

Learning focus:
React Router, forms, Zod validation, URL state, basic frontend tests.


Milestone 3: Full-Stack Integration
===================================

I1: Connect Frontend to Backend API
-----------------------------------

Replace local production data with backend data.

Add:
- Central typed API client.
- Loading, empty, error, and validation states.
- Backend-backed detail route.
- Mapping from API validation errors to form fields.

Definition of Done:
- Create/update workday through the UI.
- Add/delete pauses through the UI.
- Refreshing the browser keeps data.
- API calls are centralized.

Learning focus:
API clients, async behavior, frontend/backend contracts, error handling.


I2: React Query Server State
----------------------------

Introduce TanStack Query after manual integration works.

Hooks:
- useWorkDays
- useWorkDay
- useCreateWorkDay
- useUpdateWorkDay
- useAddPause
- useDeletePause

Definition of Done:
- Manual loading/error duplication is reduced.
- Cache invalidation works after writes.
- Query key strategy is documented.

Learning focus:
Server state, query keys, mutations, cache invalidation.


I3: Weekly and Monthly Summaries
--------------------------------

Add reporting features instead of Elasticsearch/search.

Backend:
- GET /api/reports/week?date=YYYY-MM-DD
- GET /api/reports/month?month=YYYY-MM

Frontend:
- Weekly summary page.
- Monthly summary page.
- Totals for worked hours, overtime, undertime, and average daily work time.

Definition of Done:
- Weekly and monthly reports are calculated from MySQL data.
- UI clearly shows days below, equal to, or above target.
- Report URLs are reloadable/shareable.

Learning focus:
Aggregation, date ranges, server-side reporting, URL query params.


I4: Import/Export
-----------------

Add a small but realistic data portability feature.

Backend:
- Export workdays as JSON.
- Import workdays from JSON.
- Validate imported data.
- Report created, updated, skipped, and failed rows.

Frontend:
- Download export file.
- Upload import file.
- Show import result summary.

Definition of Done:
- Invalid import does not corrupt existing data.
- Export format is documented.
- Imported days appear in reports.

Learning focus:
Serialization, file upload/download, partial success, batch validation.


Milestone 4: Quality and AI Review
==================================

Q1: Hardening and Final Review
------------------------------

Prepare the app for AI review as if it were a small real product.

Add or verify:
- PHPUnit coverage for backend calculation and API flows.
- Vitest/React Testing Library coverage for forms and summary UI.
- Optional Playwright test for the main flow: create workday, add pause, verify suggested stop time, finish day.
- Responsive layout for mobile and desktop.
- Accessibility basics: labels, keyboard navigation, visible errors, contrast.
- docs/architecture.md explaining data flow and trade-offs.
- docs/review-notes.md with self-review notes and known limitations.

Definition of Done:
- Fresh clone setup works from README.
- Main user flow is covered by tests.
- AI reviewer can understand the architecture from docs.
- You can explain where calculations happen and why.

Learning focus:
Quality gates, self-review, documentation, accessibility, E2E testing.


Suggested PR Sequence
=====================

Use small GitHub PRs even when reviewing with AI:

1. b1-backend-skeleton
2. b2-cli-file-storage
3. b3-validation-tests
4. b4-mysql-api
5. f1-frontend-setup
6. f2-local-ui
7. f3-routing-forms
8. i1-api-integration
9. i2-react-query
10. i3-reports
11. i4-import-export
12. q1-hardening-final-review


What I Would Deliberately Skip From The Team Plan
=================================================

For your solo version, skip these unless you specifically want extra difficulty:

- Elasticsearch. Reports and date filtering are more useful for this app and simpler to reason about.
- Native WebView embedding. It is less relevant if the goal is personal full-stack practice.
- Complex team branch model. Normal GitHub feature branches are enough.
- Large review ceremony. Replace it with concise PR descriptions, screenshots, test evidence, and AI review prompts.


Final App Capabilities
======================

At the end, the app should let you:

- Track start time, pauses, and end time per day.
- See when to stop working to reach 8h net work.
- Override target hours for unusual days.
- See overtime and undertime per day.
- Review weekly and monthly balances.
- Import/export your workday history.
- Run backend, frontend, and tests from documented commands.
