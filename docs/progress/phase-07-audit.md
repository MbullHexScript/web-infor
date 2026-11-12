# Phase 07 Audit - Student Life, Admissions, Facilities, Contact

Date: 2026-07-12

## Sources

| URL | Content type | Title | Year | Person | Level | Media | Verification | Translation | Target URL | Migration note |
| --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- |
| https://informatika.fsainsuinbanten.my.id/ | achievements | Inventaris Prestasi Website Lama | - | - | - | pending | pending | pending | /id/kemahasiswaan/prestasi | Needs manual privacy review before publish. |
| https://informatika.fsainsuinbanten.my.id/ | facilities | Inventaris Fasilitas Website Lama | - | - | - | pending | pending | pending | /id/fasilitas | Device/specification claims are not imported automatically. |
| https://informatika.fsainsuinbanten.my.id/ | alumni | Inventaris Alumni Website Lama | - | - | - | pending | pending | pending | /id/kemahasiswaan/alumni | Alumni stories require consent before publication. |
| https://hmps-pink.vercel.app/ | hmps | Website HMPS Informatika | - | - | - | external website | pending | pending | /id/kemahasiswaan/hmps | External link only; no iframe and no full copy. |
| https://uinbanten.ac.id/ | admissions | Informasi PMB resmi UIN | - | - | - | external website | pending | pending | /id/pmb | Admission path names, dates, fees, quotas, and requirements must be entered from official sources. |

## Rejected Content

| Source URL | Content type | Reason |
| --- | --- | --- |
| https://informatika.fsainsuinbanten.my.id/ | student-life:unverified-personal-data | Student/alumni personal data, full identifiers, private contacts, and sensitive certificates are rejected from automatic migration without consent and verification. |

## Notes

- No official achievements, scholarships, competitions, alumni, facility specifications, PMB dates, tuition, quota, or admission fees were fabricated.
- Legacy commands write import reports to `storage/logs/imports/student-life/`.
- Dry-run mode does not write `content_migrations`.
