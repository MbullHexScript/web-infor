# Event Management

Agenda menggunakan tabel `events`.

- Status dihitung dari `start_at`, `end_at`, dan `status_override`.
- Timezone default: `Asia/Jakarta`.
- Meeting URL tidak menjadi data analitik dan hanya tampil pada detail bila disediakan untuk acara publik.
- Endpoint `.ics`: `/events/{id}/ics`.
- Google Calendar URL dibuat dari data agenda publik.
