#!/usr/bin/env bash
# Prepare a staging (or production) host for this application.
#
# Run it ON THE SERVER, from the application root - the directory containing
# artisan. It checks the host, imports the schema, wires .env and warms Laravel.
# It is idempotent: re-running it never drops data and never overwrites an
# existing .env.
#
#   ./tools/setup-server.sh --db-name mmheritagehotel --db-user mmheritage \
#       --db-pass 'secret' --url https://staging.example.com
#
#   --skip-db      configure the app but leave the database alone
#   --fresh-db     DROP and re-create the database before importing (destructive!)
#
set -euo pipefail

APP_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$APP_ROOT"

DB_NAME=""; DB_USER=""; DB_PASS=""; APP_URL_ARG=""; DB_HOST="127.0.0.1"
SKIP_DB=0; FRESH_DB=0; PHP_BIN="${PHP_BIN:-php}"

while [ $# -gt 0 ]; do
    case "$1" in
        --db-name) DB_NAME="$2"; shift 2;;
        --db-user) DB_USER="$2"; shift 2;;
        --db-pass) DB_PASS="$2"; shift 2;;
        --db-host) DB_HOST="$2"; shift 2;;
        --url)     APP_URL_ARG="$2"; shift 2;;
        --php)     PHP_BIN="$2"; shift 2;;
        --skip-db) SKIP_DB=1; shift;;
        --fresh-db) FRESH_DB=1; shift;;
        *) echo "unknown argument: $1" >&2; exit 2;;
    esac
done

step() { printf '\n== %s\n' "$1"; }
ok()   { printf '   ok    %s\n' "$1"; }
warn() { printf '   warn  %s\n' "$1"; }
die()  { printf '   FAIL  %s\n' "$1" >&2; exit 1; }

# ---------------------------------------------------------------- 1. the host
step "Host"
[ -f artisan ] || die "run this from the application root (artisan not found in $APP_ROOT)"

PHP_VER="$($PHP_BIN -r 'echo PHP_VERSION;' 2>/dev/null || true)"
[ -n "$PHP_VER" ] || die "PHP not found - pass --php /path/to/php (cPanel: /opt/cpanel/ea-php81/root/usr/bin/php)"
case "$PHP_VER" in
    7.[3-9]*|8.*) ok "PHP $PHP_VER";;
    *) die "PHP $PHP_VER is not supported by Laravel 8 (needs 7.3+ / 8.x)";;
esac

MISSING=""
for ext in pdo pdo_mysql mbstring openssl tokenizer xml ctype json bcmath fileinfo gd zip; do
    $PHP_BIN -m | grep -qi "^${ext}$" || MISSING="$MISSING $ext"
done
[ -z "$MISSING" ] && ok "all required extensions loaded" || die "missing PHP extensions:$MISSING"

for d in storage/app storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache; do
    mkdir -p "$d"
done
if chmod -R ug+rwX storage bootstrap/cache 2>/dev/null; then ok "storage/ and bootstrap/cache are writable"; else warn "could not chmod storage - fix permissions manually"; fi

if [ -d vendor ]; then
    ok "vendor/ is present (this repository commits it - no composer install needed)"
else
    warn "vendor/ is missing; run: composer install --no-dev --optimize-autoloader"
fi

# -------------------------------------------------------------- 2. the .env
step "Environment file"
FRESH_ENV=0
if [ -f .env ]; then
    ok ".env already exists - leaving it untouched"
else
    [ -f .env.example ] || die ".env.example is missing"
    cp .env.example .env
    FRESH_ENV=1
    ok "created .env from .env.example"
fi

set_env() { # set_env KEY VALUE - replace or append, never duplicate
    local key="$1" value="$2"
    if grep -qE "^${key}=" .env; then
        # Delimiters chosen so the value can contain / and & but not a newline.
        sed -i.bak "s|^${key}=.*|${key}=${value}|" .env && rm -f .env.bak
    else
        printf '\n%s=%s\n' "$key" "$value" >> .env
    fi
}

[ -n "$APP_URL_ARG" ] && set_env APP_URL "$APP_URL_ARG"
set_env APP_ENV production
set_env APP_DEBUG false
if [ -n "$DB_NAME" ] && [ "$SKIP_DB" -eq 0 ]; then
    set_env DB_CONNECTION mysql
    set_env DB_HOST "$DB_HOST"
    set_env DB_DATABASE "$DB_NAME"
    set_env DB_USERNAME "$DB_USER"
    # Quote: a password may contain spaces or '#'.
    set_env DB_PASSWORD "\"$DB_PASS\""
fi

if grep -qE "^APP_KEY=$" .env; then
    $PHP_BIN artisan key:generate --force >/dev/null
    ok "generated APP_KEY"
else
    ok "APP_KEY already set"
fi

# ---------------------------------------------------------- 3. the database
if [ "$SKIP_DB" -eq 1 ]; then
    step "Database (skipped)"
else
    step "Database"
    DUMP="database/mmheritagehotel_db.sql"
    [ -f "$DUMP" ] || die "$DUMP not found - the schema ships as a SQL dump, not migrations"

    DB_CMD=($PHP_BIN -r '
        $env = parse_ini_file(".env", false, INI_SCANNER_RAW);
        echo implode("\t", [
            $env["DB_HOST"] ?? "127.0.0.1",
            $env["DB_DATABASE"] ?? "",
            $env["DB_USERNAME"] ?? "",
            $env["DB_PASSWORD"] ?? "",
        ]);
    ')
    IFS=$'\t' read -r H N U P <<< "${DB_CMD[@]}"
    [ -n "$N" ] || die "no DB_DATABASE in .env - pass --db-name"

    if ! command -v mysql >/dev/null 2>&1; then
        die "mysql client not found. Import manually:
        mysql -h $H -u $U -p $N < $DUMP"
    fi

    if [ "$FRESH_DB" -eq 1 ]; then
        printf '   dropping and recreating %s\n' "$N"
        mysql -h "$H" -u "$U" -p"$P" -e "DROP DATABASE IF EXISTS \`$N\`; CREATE DATABASE \`$N\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
    else
        mysql -h "$H" -u "$U" -p"$P" -e "CREATE DATABASE IF NOT EXISTS \`$N\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
    fi
    ok "database $N exists"

    TABLES=$(mysql -h "$H" -u "$U" -p"$P" -N -B -e "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema='$N';")
    if [ "$TABLES" -gt 0 ] && [ "$FRESH_DB" -eq 0 ]; then
        warn "database already has $TABLES tables - not importing over it (use --fresh-db to reset)"
    else
        mysql -h "$H" -u "$U" -p"$P" "$N" < "$DUMP"
        IMPORTED=$(mysql -h "$H" -u "$U" -p"$P" -N -B -e "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema='$N';")
        ok "imported $DUMP ($IMPORTED tables)"
    fi

    # The app cannot be reached without this row: the login page reads it.
    ROWS=$($PHP_BIN artisan tinker --execute='echo \Illuminate\Support\Facades\DB::table("users")->count();' 2>/dev/null | tail -1 | tr -dc '0-9' || echo "")
    [ -n "$ROWS" ] && ok "users table reachable ($ROWS accounts)" || warn "could not query users - check DB credentials in .env"
fi

# -------------------------------------------------------- 4. Laravel caches
step "Laravel"
$PHP_BIN artisan storage:link >/dev/null 2>&1 && ok "public/storage linked" || warn "storage:link failed (public/uploads may be used directly)"
$PHP_BIN artisan optimize:clear >/dev/null && ok "caches cleared"
$PHP_BIN artisan route:cache >/dev/null 2>&1 || warn "route:cache failed (CLOSURES_FIX) - the app still runs without it"
$PHP_BIN artisan config:cache >/dev/null 2>&1 && ok "config cached" || warn "config:cache failed - check .env syntax"

step "Next"
cat <<'EOF'
   1. Point the web server document root at  <app>/public  (not the app root).
   2. Give storage/ and bootstrap/cache write access to the PHP-FPM user.
   3. Run the smoke test from your workstation:
        BASE=https://your-host EMAIL=<admin email> PASS=<admin pass> ./tools/staging-smoke.sh
   4. Deploy updates with: Actions -> Deploy (staging) -> Run workflow.
      The workflow backs up, syncs, clears caches and health-checks /login,
      rolling back automatically if the check fails.
EOF
