# Cloudflare Plan

## Recommended Settings

- SSL mode: Full Strict.
- Always Use HTTPS: enabled.
- Automatic HTTPS Rewrites: enabled.
- Brotli: enabled.
- HTTP/2: enabled.
- HTTP/3: optional.
- WAF managed rules: enabled.

## Cache Rules

Cache:

- CSS, JS, fonts, public images, icons, thumbnails.

Bypass:

- `/admin/*`
- `/admin/login`
- contact form POST routes
- search requests when dynamic
- preview/private/upload routes
- authenticated session responses

## Rate Limiting

- Admin login.
- Contact form.
- Search.

Do not challenge all Indonesian traffic aggressively.

Real visitor IPs require Nginx real-IP configuration. Start from `deploy/nginx/cloudflare-real-ip.conf` and keep Cloudflare IP ranges updated.
