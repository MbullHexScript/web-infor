# Final Deployment Test Report

Date: 2026-07-12

This report covers local deployment-readiness checks only. Staging and production checks require server access.

| Test | Status | Note |
| --- | --- | --- |
| Unit/feature tests | passed | 74 tests, 351 assertions |
| Frontend lint | passed | `npm.cmd run lint` |
| TypeScript | passed | `npm.cmd run typecheck` |
| Production build | passed | `npm.cmd run build` |
| Route cache | passed | `php artisan route:cache` succeeded |
| Sitemap validation | passed | 18 URLs generated |
| Backup verify | passed | local backup metadata verified |
| Restore test | blocked | Requires non-production server backup restore |
| Deployment script dry-run | blocked locally | Scripts created; `bash` unavailable in PATH |
| Rollback script test | not executed on server | Script created |
| DNS validation | blocked | Requires DNS access |
| SSL validation | blocked | Requires certificate/domain |
| Cloudflare validation | blocked | Requires Cloudflare access |
| Email test | blocked | Requires SMTP credentials |
| Queue test | pending server | `queue:monitor` available |
| Scheduler test | pending server | Requires cron |
| Health check | pending deployed URL |  |
| Dependency audit | failed | Vite/esbuild advisory; fix requires breaking upgrade |
