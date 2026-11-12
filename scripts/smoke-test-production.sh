#!/usr/bin/env bash
set -Eeuo pipefail

BASE_URL="${BASE_URL:-https://informatika.uinbanten.ac.id}"

paths=(
  "/"
  "/health"
  "/id"
  "/en"
  "/id/profil"
  "/id/profil/dosen"
  "/id/akademik/kurikulum"
  "/id/akademik/mata-kuliah"
  "/id/akademik/rps"
  "/id/informasi/berita"
  "/id/riset"
  "/id/riset/rumah-jurnal"
  "/id/kemahasiswaan"
  "/id/pmb"
  "/id/kontak"
  "/admin/login"
  "/sitemap.xml"
  "/robots.txt"
)

for path in "${paths[@]}"; do
  url="${BASE_URL}${path}"
  code="$(curl -k -L -s -o /dev/null -w "%{http_code}" "$url")"
  if [[ "$code" -lt 200 || "$code" -ge 400 ]]; then
    echo "FAIL $code $url"
    exit 1
  fi
  echo "OK $code $url"
done

echo "Smoke test passed for $BASE_URL"
