# Queue and Scheduler

## Queue

Template:

```bash
sudo cp deploy/systemd/informatika-queue.service /etc/systemd/system/
sudo systemctl daemon-reload
sudo systemctl enable --now informatika-queue
sudo systemctl status informatika-queue
```

Monitor:

```bash
php artisan queue:monitor database --max=100
php artisan queue:failed
```

## Scheduler

Template:

```bash
sudo cp deploy/cron/informatika-scheduler /etc/cron.d/informatika-scheduler
sudo systemctl reload cron
```

Verify that `php artisan schedule:run` runs every minute.

The cron template is stored at `deploy/cron/informatika-scheduler`.
