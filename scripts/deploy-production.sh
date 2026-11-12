#!/usr/bin/env bash
set -Eeuo pipefail

APP_ROOT="${APP_ROOT:-/var/www/informatika}"
RELEASE="${RELEASE:-$(date +%Y%m%d%H%M%S)}"
RELEASE_DIR="$APP_ROOT/releases/$RELEASE"
CURRENT_LINK="$APP_ROOT/current"
SHARED_DIR="$APP_ROOT/shared"
REPO_URL="${REPO_URL:?Set REPO_URL}"
REF="${REF:-main}"
KEEP_RELEASES="${KEEP_RELEASES:-5}"
LOG_DIR="${LOG_DIR:-/var/log/informatika-deploy}"

PHP_BIN="${PHP_BIN:-php}"
COMPOSER_BIN="${COMPOSER_BIN:-composer}"
NPM_BIN="${NPM_BIN:-npm}"

mkdir -p "$LOG_DIR"
LOG_FILE="$LOG_DIR/deploy-production-$RELEASE.log"
exec > >(tee -a "$LOG_FILE") 2>&1

step() { printf '\n==> %s\n' "$1"; }
fail() { echo "$1"; exit 1; }
require_cmd() { command -v "$1" >/dev/null 2>&1 || fail "Missing command: $1"; }
trap 'echo "Production deployment failed before completion. Current symlink was not changed unless noted. See $LOG_FILE"; exit 1' ERR

require_cmd git
require_cmd "$PHP_BIN"
require_cmd "$COMPOSER_BIN"
require_cmd "$NPM_BIN"

step "Creating release directory"
mkdir -p "$APP_ROOT/releases" "$SHARED_DIR/storage" "$SHARED_DIR/bootstrap-cache" "$SHARED_DIR/backups"
git clone --depth 1 --branch "$REF" "$REPO_URL" "$RELEASE_DIR"
cd "$RELEASE_DIR"

step "Linking shared files"
test -f "$SHARED_DIR/.env" || fail "Missing shared .env"
ln -sfn "$SHARED_DIR/.env" .env
rm -rf storage bootstrap/cache
ln -sfn "$SHARED_DIR/storage" storage
ln -sfn "$SHARED_DIR/bootstrap-cache" bootstrap/cache

step "Installing dependencies and building"
"$COMPOSER_BIN" install --no-dev --prefer-dist --optimize-autoloader --no-interaction
"$NPM_BIN" ci
"$NPM_BIN" run build

step "Pre-deployment quality gates"
"$PHP_BIN" artisan test --stop-on-failure
"$PHP_BIN" artisan quality:audit
"$PHP_BIN" artisan curriculum:validate

step "Entering maintenance mode on current release if available"
if [ -L "$CURRENT_LINK" ]; then
  "$PHP_BIN" "$CURRENT_LINK/artisan" down --render="errors::503" || true
fi

step "Backup before migration"
"$PHP_BIN" artisan backup:run
"$PHP_BIN" artisan backup:verify

step "Running migrations"
"$PHP_BIN" artisan migrate --force

step "Caching and generating production assets"
"$PHP_BIN" artisan storage:link || true
"$PHP_BIN" artisan optimize:clear
"$PHP_BIN" artisan config:cache
"$PHP_BIN" artisan route:cache
"$PHP_BIN" artisan view:cache
"$PHP_BIN" artisan sitemap:generate
"$PHP_BIN" artisan sitemap:validate

step "Switching current symlink"
ln -sfn "$RELEASE_DIR" "$CURRENT_LINK"

step "Restarting workers"
"$PHP_BIN" "$CURRENT_LINK/artisan" queue:restart || true
sudo systemctl reload php8.2-fpm || true

step "Leaving maintenance mode"
"$PHP_BIN" "$CURRENT_LINK/artisan" up || true

step "Health check"
curl -fsS "${APP_URL:-https://informatika.uinbanten.ac.id}/health" >/dev/null

step "Cleaning old releases"
find "$APP_ROOT/releases" -mindepth 1 -maxdepth 1 -type d | sort -r | tail -n +"$((KEEP_RELEASES + 1))" | xargs -r rm -rf

step "Production deployment complete"
echo "Release: $RELEASE"
echo "Log: $LOG_FILE"
