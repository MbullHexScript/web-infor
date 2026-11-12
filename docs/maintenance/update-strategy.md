# Update Strategy

- Run Composer and NPM updates on a branch or staging first.
- Never run major upgrades directly in production.
- Back up database and media before production updates.
- Run full quality gates after updates.
- Deploy to staging and complete smoke test.
- Keep previous release available for rollback.
- Review Laravel, PHP, PostgreSQL, Nginx, OS, and Cloudflare changes separately.
- Track dependency advisories, including Vite/esbuild, before production go-live.
