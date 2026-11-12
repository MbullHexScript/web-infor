# Monitoring Plan

Monitor:

- `/`
- `/health`
- `/id`
- `/en`
- `/id/akademik/rps`
- `/admin/login`
- Queue failures.
- Disk usage.
- Database connectivity.
- Backup age and verification.
- SSL expiry.
- HTTP 5xx and response time.

Alert:

- Email or official technical channel.
- Uptime frequency: 1-5 minutes.
- Do not monitor heavy pages too frequently.

Optional error monitoring:

- Sentry, Bugsnag, GlitchTip, or institutional tool.
- Redact passwords, tokens, secrets, contact message content, and recovery codes.
