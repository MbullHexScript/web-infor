# Contact Management

Contact messages are stored in `contact_messages`.

Public form protections:

- Required name, valid email, category, subject, message, and consent.
- Optional phone.
- Honeypot field.
- Rate limit per IP.
- HTML is stripped before storage.
- Mail delivery failure does not delete the saved database record.

Admin features:

- Inbox dashboard at `/admin/contact`.
- Status changes: read, replied, archived, spam.
- Soft delete.
- CSV export excludes IP address and user-agent by default.

Pending:

- Verified phone number.
- Verified service hours.
- Verified map embed URL.
- Safe reply workflow if direct email replies are later needed.
