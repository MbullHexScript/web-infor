# Achievement Management

Achievements live in `achievements`.

Public visibility requires:

- `is_public=true`
- `verification_status=verified`
- `published_at` not null

Privacy rules:

- Full NIM must not be exposed publicly.
- Certificates are hidden unless `certificate_is_public=true`.
- Source URL and verification status should be recorded during curation.

Supported filters:

- Search
- Year
- Level
- Achievement type
