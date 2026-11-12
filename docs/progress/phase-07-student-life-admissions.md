# Phase 07 - Student Life, Admissions, Facilities, Contact

Implemented:

- Public bilingual student life module.
- Achievements with search/filter, detail, and privacy-safe resources.
- Scholarships and competitions with verified-only public visibility.
- MBKM and internship page from dynamic `pages` content.
- Alumni stories with consent guard.
- HMPS page with safe external link to `https://hmps-pink.vercel.app/`.
- Facilities listing and detail pages.
- Admissions landing page with career roadmap, FAQ, conditional official portal, and no fake dates/fees.
- Contact page with form validation, consent, honeypot, rate limit, safe storage, and mail-failure tolerance.
- Admin dashboards for student life, facilities, admissions, and contact inbox.
- Contact inbox status actions: read, replied, archived, spam, soft delete, and privacy-safe CSV export.
- Legacy commands:
  - `php artisan student-life:inspect-legacy`
  - `php artisan student-life:import-legacy --type=achievements --dry-run`
  - `php artisan student-life:import-legacy --type=facilities --dry-run`
  - `php artisan student-life:import-legacy --type=alumni --dry-run`
  - `php artisan student-life:import-legacy --all --dry-run`
  - `php artisan student-life:import-legacy --all`

Verification:

- `php artisan migrate:fresh --seed`
- `vendor/bin/pint --test`
- `npm.cmd run format:check`
- `npm.cmd run lint`
- `npm.cmd run typecheck`
- `php artisan test`
- `npm.cmd run build`

Result:

- 64 tests passed, 284 assertions.
- Production build completed.

Pending official data:

- Real achievements and safe certificate media.
- Scholarship provider data and official registration links.
- Competition dates, deadlines, and posters.
- Alumni stories with consent.
- Facility photos, capacity, location, and verified specifications.
- Official PMB portal URL, admission paths, dates, fees, quotas, and requirements.
- Verified map embed URL, phone number, and service hours.
