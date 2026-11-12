# Security

## Fondasi Saat Ini

- Session authentication Laravel untuk satu admin.
- Tidak ada registrasi publik.
- CSRF protection aktif.
- Security headers aktif, termasuk CSP, COOP, CORP, Referrer-Policy, Permissions-Policy, dan admin noindex.
- 2FA TOTP tersedia untuk admin.
- Recovery codes tersedia dan dapat diregenerasi.
- Upload media divalidasi berdasarkan ekstensi, MIME, ukuran, konten image, dan signature PDF.
- Redirect legacy divalidasi untuk mencegah target berbahaya, loop, dan chain.
- Audit log meredaksi password, secret, token, dan recovery code.
- `/health` tidak mengekspos secret.
- Secret wajib melalui environment.
- Password admin tidak disimpan di repository.

## Pelaporan

Laporkan isu keamanan ke `informatika@uinbanten.ac.id`.

## Catatan Produksi

Aktifkan HTTPS, secure cookies, trusted proxy, backup terenkripsi di luar server, monitoring error, dan review CSP sesuai aset produksi.

## Deployment Security

- Production `.env` must stay on the server only.
- Cloudflare API tokens must not be committed.
- Staging responses include noindex headers when `APP_ENV=staging`.
- HSTS preload is opt-in with `HSTS_PRELOAD=true`; keep it false until all subdomains are verified for HTTPS.
- Current dependency audit reports Vite/esbuild advisories that require a breaking upgrade decision before production approval.
