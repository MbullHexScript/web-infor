# Accessibility

## Academic Module

Tab semester memakai role tablist/tab, filter RPS memiliki label, tabel mata kuliah memiliki caption, card mobile menjaga informasi penting, dan PDF preview memiliki fallback ketika file belum tersedia.

## Profile Module

Foto dosen memiliki alt text, timeline menggunakan list, filter dosen memiliki label, card dosen dapat diakses lewat link keyboard, dan status verifikasi tidak hanya mengandalkan warna.

Fondasi saat ini mencakup semantic layout, skip link, focus ring terlihat, button accessible name, responsive navigation, dan reduced motion.

Audit WCAG 2.2 AA dilakukan bertahap pada setiap fase fitur.

Fase 2 menambahkan:

- Skip link pada homepage.
- Mobile drawer dengan Escape close, focus loop, dan body scroll lock.
- Button dengan accessible name untuk search, theme, language, dan drawer.
- External link memakai `target="_blank"` hanya saat URL benar-benar eksternal dan `rel="noopener noreferrer"`.
- Visual QA memeriksa tidak ada horizontal overflow pada 7 viewport.

## Phase 07

- Status prestasi, beasiswa, kompetisi, pesan, dan jalur PMB memakai label teks.
- Form kontak memiliki label, error per field, consent, success state, dan error alert.
- FAQ memakai elemen native `details`/`summary`.
- Map memiliki fallback teks ketika embed belum diverifikasi.
- HMPS external link memakai `rel="noopener noreferrer"`.

## Phase 08

- Admin metrics use text labels and icons with accessible names where needed.
- Data tables include empty states and clear action labels.
- Upload, SEO, redirect, settings, audit, account, and operations pages keep form labels and validation summaries visible.
- Media items require alt text review as part of audit.

## Phase 09

Accessibility final report tersedia di `docs/qa/accessibility-final-report.md`. Status saat ini adalah pending manual staging pass untuk keyboard, contrast, zoom, mobile reflow, dan screen reader labels.
