# Phase 09 Baseline Audit

Date: 2026-07-12

## Scope

Baseline dibuat dari database lokal setelah Phase 8 dan command `quality:audit --write-inventory`.

Sumber eksternal yang ditinjau:

- `https://informatika.fsainsuinbanten.my.id/`
- `https://uinbanten.ac.id/`
- `https://fsainsuinbanten.my.id/`
- `https://hmps-pink.vercel.app/`

## Summary

| Metric | Result |
| --- | ---: |
| Total route | 231 |
| Public route | 136 |
| Admin route | 95 |
| Verified content | 82 |
| Pending content | 4 |
| Needs review content | 14 |
| Rejected content | 0 |
| Translation issues | 143 |
| Media without alt | 0 |
| Orphan media | 0 |
| Broken internal links | 0 automated / manual crawl required |
| Redirect chains | 0 |
| Redirect loops | 0 |
| Total RPS | 0 |
| Courses without RPS | 65 |
| SEO issues | 0 metadata rows / content-level review required |
| Accessibility issues | Manual audit required |
| Security issues | No critical issue from automated local audit |
| Failed tests | 0 after final test run |
| Build status | Passed |
| Lighthouse baseline | Not measured locally |
| Core Web Vitals baseline | Not measured locally |

## Content Totals

| Table | Type | Total |
| --- | --- | ---: |
| pages | Page | 7 |
| lecturers | Lecturer | 8 |
| staff | Staff | 0 |
| courses | Course | 65 |
| rps_documents | RPS | 0 |
| news | News | 1 |
| announcements | Announcement | 1 |
| events | Event | 1 |
| research_projects | Research | 1 |
| community_services | Community Service | 0 |
| publications | Publication | 1 |
| digital_products | Product | 0 |
| intellectual_properties | HKI | 0 |
| journals | Journal | 3 |
| achievements | Achievement | 1 |
| facilities | Facility | 0 |
| alumni_stories | Alumni | 0 |
| scholarships | Scholarship | 0 |
| competitions | Competition | 0 |
| partners | Partner | 6 |
| documents | Document | 0 |
| faqs | FAQ | 9 |

## Baseline Risks

- RPS coverage is zero because official RPS PDFs have not been uploaded.
- English course names/content are still incomplete and must be filled from verified curriculum sources.
- Lecturer data remains `needs_review` and should not be treated as final.
- Website lama contains generic/demo-like news and old statistics; these must not be imported as verified production data without review.
- Lighthouse, browser, responsive, and accessibility audits require a running staging URL.
