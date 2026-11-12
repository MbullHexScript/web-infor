# Session Management

Admin authentication uses Laravel session auth.

Security expectations:

- No public registration.
- One admin account seeded from environment.
- 2FA can be enabled for the admin account.
- Recovery codes can be regenerated from `/admin/account`.
- Password changes invalidate other sessions.

Recommended production settings:

```env
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=lax
```
