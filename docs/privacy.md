# Privacy

## Phase 07 Student and Alumni Data

Rules implemented:

- Public achievement resources do not expose `student_identifiers_json`.
- Certificates are hidden unless `certificate_is_public=true`.
- Alumni stories require `consent_verified=true`.
- Contact messages are private admin data and are never rendered publicly.
- Contact CSV export excludes IP address and user-agent by default.

Pending policy work:

- Formal retention policy for IP address and user-agent.
- Admin workflow for takedown/removal requests.
- Consent evidence storage for alumni stories.

## Phase 08 Admin Operations

Rules implemented:

- Audit log redacts sensitive keys before storage.
- Health endpoint avoids secrets and private paths.
- Contact export remains limited.
- Media metadata may include credit/caption but should not include private identifiers.
- Recovery codes are regenerated and shown only in the account security flow, not stored in audit logs as plaintext.

## Phase 09

Final migration rules reject full NIM, private contacts, sensitive certificates, alumni data without consent, unverified PMB claims, and unverified institutional statistics.

## Phase 10

Analytics must remain disabled until approved. If enabled, do not track admin pages, contact form content, or sensitive data. Staging must not use production analytics.
