# Health Check

The health endpoint is available at `/health`.

It reports minimal JSON status for:

- application status
- database
- cache
- storage

It must not expose secrets, environment values, stack traces, user data, or private paths.

Use `/health` for uptime monitoring and deployment smoke tests.
