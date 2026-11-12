# Maintenance Mode

Maintenance mode can be toggled from `/admin/operations`.

The backend uses Laravel `down` and `up` commands. The bypass secret is read from:

```env
APP_MAINTENANCE_SECRET=change-this
```

Set a strong production value and keep it out of public documentation.
