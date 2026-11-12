# Document Management

Pusat unduhan menggunakan `document_categories` dan `documents`.

- Dokumen publik wajib `status=published`, `is_public=true`, tidak soft deleted, dan memiliki file pada disk public.
- Download melalui `/documents/{id}/download`; path storage tidak dibocorkan.
- Download count di-increment pada request download valid.
- Draft dan path traversal ditolak.
