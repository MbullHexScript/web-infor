# Production Email

From:

- Name: Program Studi Informatika
- Address: `informatika@uinbanten.ac.id`

Checks:

- SMTP credentials stored only in production `.env`.
- SPF configured.
- DKIM configured if available.
- DMARC configured if available.
- `php artisan mail:test --to=<technical-email>` passes.
- Contact form stores messages even if email fails.

Do not use a personal Gmail sender for production unless institution policy explicitly allows it.
