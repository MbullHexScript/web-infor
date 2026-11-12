# Performance Final Report

Date: 2026-07-12

Production build passes locally.

Build output includes the main app bundle around 365 kB before gzip and around 119 kB gzip in the current local build. This is acceptable for UAT, but Lighthouse must still validate real LCP/CLS/INP on staging.

Lighthouse and Core Web Vitals are not measured in this local phase because they require a stable staging or production URL.

Pages to measure:

- Homepage
- Curriculum
- Courses
- RPS
- Lecturers
- News
- Research
- Journal Hub
- Student Life
- Admissions
- Contact

Target:

- Desktop performance >= 90.
- Mobile performance >= 80.
- Accessibility >= 90.
- Best Practices >= 90.
- SEO >= 90.
