#!/usr/bin/env bash
set -Eeuo pipefail

APP_DIR="${APP_DIR:-/var/www/informatika-staging/current}"
LOG_DIR="${LOG_DIR:-/var/log/informatika-deploy}"
PHP_BIN="${PHP_BIN:-php}"
COMPOSER_BIN="${COMPOSER_BIN:-composer}"
NPM_BIN="${NPM_BIN:-npm}"

mkdir -p "$LOG_DIR"
LOG_FILE="$LOG_DIR/deploy-staging-$(date +%Y%m%d-%H%M%S).log"
exec > >(tee -a "$LOG_FILE") 2>&1

step() { printf '\n==> %s\n' "$1"; }
require_cmd() { command -v "$1" >/dev/null 2>&1 || { echo "Missing command: $1"; exit 1; }; }

trap 'echo "Deployment failed. See $LOG_FILE"; exit 1' ERR

require_cmd "$PHP_BIN"
require_cmd "$COMPOSER_BIN"
require_cmd "$NPM_BIN"

step "Entering app directory"
cd "$APP_DIR"

step "Installing PHP dependencies"
"$COMPOSER_BIN" install --no-dev --prefer-dist --optimize-autoloader --no-interaction

step "Installing Node dependencies"
"$NPM_BIN" ci

step "Building frontend"
"$NPM_BIN" run build

step "Preparing Laravel"
"$PHP_BIN" artisan key:generate --force --ansi || true
"$PHP_BIN" artisan migrate --force
"$PHP_BIN" artisan db:seed --class=UatSeeder --force
"$PHP_BIN" artisan storage:link || true
if [ -f deploy/staging-robots.txt ]; then
  cp deploy/staging-robots.txt public/robots.txt
fi

step "Caching application"
"$PHP_BIN" artisan optimize:clear
"$PHP_BIN" artisan config:cache
"$PHP_BIN" artisan route:cache
"$PHP_BIN" artisan view:cache

step "Running smoke checks"
"$PHP_BIN" artisan quality:audit --write-inventory
"$PHP_BIN" artisan sitemap:generate
"$PHP_BIN" artisan sitemap:validate
"$PHP_BIN" artisan backup:verify || true

step "Staging deployment complete"
echo "Log: $LOG_FILE"
