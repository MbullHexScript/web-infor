# DNS Plan

Do not change DNS without approval from the domain/server owner.

| Environment | Record type | Name | Target | Proxy | TTL | Status |
| --- | --- | --- | --- | --- | --- | --- |
| Staging | CNAME or A | `staging-informatika.uinbanten.ac.id` | pending server IP/name | optional | 300 during cutover | pending |
| Production | CNAME or A | `informatika.uinbanten.ac.id` | pending server IP/name | Cloudflare proxied if approved | 300 during cutover | pending |

## Cutover Notes

- Lower TTL before cutover if allowed.
- Record existing DNS before changes.
- Verify SSL before switching production traffic.
- Keep rollback DNS target documented.
