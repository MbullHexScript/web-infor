#!/usr/bin/env bash
set -Eeuo pipefail

APP_ROOT="${APP_ROOT:-/var/www/informatika}"
CURRENT_LINK="$APP_ROOT/current"
TARGET_RELEASE="${1:-}"
PHP_BIN="${PHP_BIN:-php}"

if [ -z "$TARGET_RELEASE" ]; then
  echo "Usage: $0 <release-directory-name>"
  echo "Available releases:"
  find "$APP_ROOT/releases" -mindepth 1 -maxdepth 1 -type d -printf '%f\n' | sort -r
  exit 1
fi

TARGET="$APP_ROOT/releases/$TARGET_RELEASE"
test -d "$TARGET" || { echo "Release not found: $TARGET"; exit 1; }

echo "Switching current symlink to $TARGET"
ln -sfn "$TARGET" "$CURRENT_LINK"

"$PHP_BIN" "$CURRENT_LINK/artisan" optimize:clear
"$PHP_BIN" "$CURRENT_LINK/artisan" config:cache
"$PHP_BIN" "$CURRENT_LINK/artisan" route:cache
"$PHP_BIN" "$CURRENT_LINK/artisan" view:cache
"$PHP_BIN" "$CURRENT_LINK/artisan" queue:restart || true
sudo systemctl reload php8.2-fpm || true

curl -fsS "${APP_URL:-https://informatika.uinbanten.ac.id}/health" >/dev/null
echo "Rollback complete. Database rollback was not performed."
