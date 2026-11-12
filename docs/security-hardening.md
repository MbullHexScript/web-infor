# Security Hardening

Phase 8 adds:

- Content Security Policy.
- Cross-Origin-Opener-Policy.
- Cross-Origin-Resource-Policy.
- Admin `X-Robots-Tag: noindex, nofollow`.
- Hardened upload validation.
- Redirect safety checks.
- Audit logging with redaction.
- Account recovery code regeneration.
- Health check without secret exposure.

Production must still enforce HTTPS, secure cookies, trusted proxy configuration, server-level upload limits, and off-server backups.
