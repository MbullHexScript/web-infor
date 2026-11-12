# Content Security Policy

Phase 8 sends a CSP header from `SecurityHeaders`.

The policy is intentionally conservative:

- `default-src 'self'`
- blocks plugins through `object-src 'none'`
- restricts forms to same-origin
- limits frames to explicitly allowed sources
- permits local Vite assets in local development

Review the policy before production whenever analytics, maps, video embeds, fonts, or CDN assets are added.
