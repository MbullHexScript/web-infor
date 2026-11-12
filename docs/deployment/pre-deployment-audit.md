# Pre-Deployment Audit

Date: 2026-07-12

## Release

| Item | Value |
| --- | --- |
| Release version | v1.0.0-rc.1 candidate |
| Commit hash | unavailable, `git` is not available in PATH in this environment |
| Target staging | `https://staging-informatika.uinbanten.ac.id` |
| Target production | `https://informatika.uinbanten.ac.id` |

## Current Status

| Gate | Status |
| --- | --- |
| Production build | passed locally |
| Tests | passed locally, 74 tests and 351 assertions |
| Migration | route/migration commands available; production migration not executed |
| Security | application checks passed; dependency advisory remains |
| Backup | `backup:run` and `backup:verify` passed locally |
| Data verification | not production-ready |
| UAT approval | not yet approved |

## Pending Issues

- Official RPS files are not uploaded.
- Lecturer, accreditation, partner, PMB, facility, achievement, and contact data still require official verification.
- Course English translations remain incomplete.
- `npm audit` reports Vite/esbuild advisories requiring a dependency-upgrade decision.
- Staging, DNS, SSL, Cloudflare, monitoring, queue, scheduler, and off-server backup need server access.

## Recommendation

No-go for production. Proceed to staging deployment and UAT only.

## Latest Local Audit Results

- `php artisan test`: passed, 74 tests, 351 assertions.
- `npm.cmd run lint`: passed.
- `npm.cmd run typecheck`: passed.
- `npm.cmd run build`: passed.
- `php artisan route:cache`: passed.
- `php artisan quality:audit --write-inventory`: passed.
- `php artisan curriculum:validate`: passed.
- `php artisan translations:audit`: passed with 143 known translation issues.
- `php artisan sitemap:generate`: generated 18 URLs.
- `php artisan sitemap:validate`: passed.
- `php artisan redirects:audit`: 0 warnings.
- `php artisan links:audit`: passed lightweight audit.
- `php artisan media:audit`: 1 UAT orphan, 0 missing alt.
- `php artisan backup:run`: passed.
- `php artisan backup:verify`: passed.
- `npm.cmd audit --audit-level=high`: failed because Vite/esbuild advisories require a breaking upgrade decision.
- Bash syntax check for scripts: not run locally because `bash` is not available in PATH.
