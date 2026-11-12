# Backup and Restore

Rencana backup:

- Database harian.
- Media mingguan.
- Retensi minimal 30 hari.
- Backup terenkripsi bila keluar server.
- Restore test berkala.

Backup tidak boleh disimpan di folder `public`.

## Phase 8 Local Backup Command

Phase 8 menambahkan backup metadata lokal untuk smoke test operasional:

```bash
php artisan backup:run
php artisan backup:list
php artisan backup:verify
```

Catatan:

- File backup lokal disimpan di storage aplikasi, bukan `public`.
- Checksum diverifikasi oleh `backup:verify`.
- Backup produksi tetap harus dikirim ke lokasi terenkripsi di luar server.
- Restore produksi harus diuji berkala dari backup sungguhan, bukan hanya metadata lokal.

## Phase 09 UAT Gate

Release candidate cannot be approved until:

- `php artisan backup:verify` passes.
- Off-server encrypted backup is configured for production.
- Restore procedure has been tested on staging or a disposable environment.

## Phase 10 Automation

Use `docs/deployment/backup-automation.md` for cron and monitoring guidance. Production backup must be encrypted when leaving the server and must not be stored under `public`.
