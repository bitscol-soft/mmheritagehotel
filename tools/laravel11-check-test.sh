#!/usr/bin/env bash
# =============================================================================
# tools/laravel11-check-test.sh — self-test for tools/laravel11-check.sh
#
# The readiness guard is a CI tripwire for BUGS #31: it is what stops a
# half-applied upgrade from being merged (declaring ^9 while the Kernel still
# uses $routeMiddleware makes every route 404; declaring ^11 while the runner is
# still on PHP 8.1 makes the app die at runtime). A guard that silently stops
# matching is worse than no guard, because the upgrade steps then look safe.
#
# The upgrade itself cannot be rehearsed here - it needs Composer and Packagist,
# and the lock/vendor refresh is a separate networked step. So instead each
# assertion builds a throwaway tree in the shape a half-applied step would leave
# behind, and asserts that the guard both fails AND says why.
#
# Exit codes: 0 = every assertion passed, 1 = a regression, 2 = bad invocation.
# =============================================================================
set -u

HERE="$(cd "$(dirname "$0")" && pwd)"
ROOT="$(cd "$HERE/.." && pwd)"
GUARD="$ROOT/tools/laravel11-check.sh"

if [ ! -f "$GUARD" ]; then
    echo "laravel11-check-test: $GUARD is missing" >&2
    exit 2
fi
if ! command -v node >/dev/null 2>&1; then
    echo "laravel11-check-test: node is required (the guard reads composer.json with it)" >&2
    exit 2
fi

TMP="$(mktemp -d "${TMPDIR:-/tmp}/l11check.XXXXXX")" || exit 2
trap 'rm -rf "$TMP"' EXIT

PASS=0
FAIL=0

ok()   { PASS=$((PASS + 1)); printf '  PASS  %s\n' "$1"; }
nope() { FAIL=$((FAIL + 1)); printf '  FAIL  %s\n' "$1"; }

# --------------------------------------------------------------- tree builder
# A tree shaped like this repo, in the state an upgrade step leaves it. Defaults
# are the CURRENT state (Laravel 8, PHP 8.1 runner) so each case only describes
# the thing under test.
build_tree() {
    local d="$1"
    rm -rf "$d"
    mkdir -p "$d/tools" "$d/app/Http" "$d/app/Console/Commands" "$d/config" \
             "$d/database" "$d/routes" "$d/module" "$d/docs" "$d/.github/workflows"
    cp "$GUARD" "$d/tools/laravel11-check.sh"

    cat > "$d/composer.json" <<'JSON'
{
    "require": {
        "php": "^7.3|^8.0",
        "fruitcake/laravel-cors": "^2.0",
        "laravel/framework": "^8.75"
    },
    "require-dev": {
        "facade/ignition": "^2.5",
        "phpunit/phpunit": "^9.5.10"
    }
}
JSON

    cat > "$d/app/Http/Kernel.php" <<'PHP'
<?php
class Kernel
{
    protected $routeMiddleware = ['auth' => \App\Http\Middleware\Authenticate::class];
}
PHP

    cat > "$d/phpunit.xml" <<'XML'
<?xml version="1.0" encoding="UTF-8"?>
<phpunit bootstrap="vendor/autoload.php">
    <coverage processUncoveredFiles="true">
        <include><directory suffix=".php">./app</directory></include>
    </coverage>
</phpunit>
XML

    printf '# runbook\n' > "$d/docs/LARAVEL-11-UPGRADE.md"
    printf "          php-version: '8.1'\n" > "$d/.github/workflows/ui-check.yml"
}

# Rewrite a key inside composer.json without re-serialising the whole file.
set_composer() {
    local file="$1" key="$2" value="$3"
    node -e '
        const fs = require("fs");
        const [file, key, value] = process.argv.slice(1);
        const json = JSON.parse(fs.readFileSync(file, "utf8"));
        const block = (json["require-dev"] && key in json["require-dev"]) ? "require-dev" : "require";
        if (value === "REMOVE") delete json[block][key];
        else { json[block] = json[block] || {}; json[block][key] = value; }
        const indent = (level) => "    ".repeat(level);
        const render = (obj, level) => Object.entries(obj)
            .map(([k, v], i, all) => indent(level + 1) + JSON.stringify(k) + ": " + JSON.stringify(v) + (i < all.length - 1 ? "," : ""))
            .join("\n");
        let out = "{\n";
        out += indent(1) + "\"require\": {\n" + render(json.require || {}, 1) + "\n" + indent(1) + "}";
        if (json["require-dev"]) {
            out += ",\n" + indent(1) + "\"require-dev\": {\n" + render(json["require-dev"], 1) + "\n" + indent(1) + "}";
        }
        out += "\n}\n";
        fs.writeFileSync(file, out);
    ' "$file" "$key" "$value"
}

# Assert the guard's exit code and, when it must fail, that it says why.
# expect <tree> <0|1> <output fragment> <label>
expect() {
    local d="$1" want="$2" needle="$3" label="$4"
    local out status
    out="$(bash "$d/tools/laravel11-check.sh" 2>&1)"
    status=$?

    if [ "$status" -ne "$want" ]; then
        nope "$label (exit $status, wanted $want)"
        printf '%s\n' "$out" | sed 's/^/        | /' | head -20
        return
    fi
    if [ -n "$needle" ] && ! printf '%s' "$out" | grep -qF -- "$needle"; then
        nope "$label (failed, but never said: $needle)"
        printf '%s\n' "$out" | sed 's/^/        | /' | head -20
        return
    fi
    ok "$label"
}

echo "laravel11-check-test: guarding $GUARD"

# ------------------------------------------------------------ 1. current state
T="$TMP/current"
build_tree "$T"
expect "$T" 0 "" "the tree as it stands today passes (L8 + PHP 8.1 runner)"

# ---------------------------------------------- 2. the L9 rename, half applied
T="$TMP/l9-kernel"
build_tree "$T"
set_composer "$T/composer.json" laravel/framework '^9.0'
set_composer "$T/composer.json" php '^8.0.2'
set_composer "$T/composer.json" fruitcake/laravel-cors REMOVE
set_composer "$T/composer.json" facade/ignition REMOVE
expect "$T" 1 'still declares $routeMiddleware' "declaring ^9 while the Kernel keeps \$routeMiddleware fails"

# ------------------------------------------------- 3. the rename, too early
T="$TMP/l8-renamed"
build_tree "$T"
cat > "$T/app/Http/Kernel.php" <<'PHP'
<?php
class Kernel
{
    protected $middlewareAliases = ['auth' => \App\Http\Middleware\Authenticate::class];
}
PHP
expect "$T" 1 'declares $middlewareAliases while' "renaming to \$middlewareAliases while still on ^8 fails"

# --------------------------------------------------- 4. abandoned CORS package
T="$TMP/l9-cors"
build_tree "$T"
set_composer "$T/composer.json" laravel/framework '^9.0'
set_composer "$T/composer.json" php '^8.0.2'
set_composer "$T/composer.json" facade/ignition REMOVE
printf '%s\n' "$(sed 's/\$routeMiddleware/$middlewareAliases/' "$T/app/Http/Kernel.php")" > "$T/app/Http/Kernel.php"
expect "$T" 1 'fruitcake/laravel-cors is still required' "keeping fruitcake/laravel-cors on ^9 fails"

# ------------------------------------------------------- 5. the PHPUnit schema
T="$TMP/l10-phpunit"
build_tree "$T"
set_composer "$T/composer.json" laravel/framework '^10.0'
set_composer "$T/composer.json" php '^8.1'
set_composer "$T/composer.json" phpunit/phpunit '^10.0'
set_composer "$T/composer.json" fruitcake/laravel-cors REMOVE
set_composer "$T/composer.json" facade/ignition REMOVE
sed 's/\$routeMiddleware/$middlewareAliases/' "$T/app/Http/Kernel.php" > "$T/k" && mv "$T/k" "$T/app/Http/Kernel.php"
expect "$T" 1 'processUncoveredFiles' "keeping the PHPUnit 9 schema on ^10.0 fails"

# -------------------------------------- 6. the L11 runner pin (the new check)
T="$TMP/l11-old-runner"
build_tree "$T"
set_composer "$T/composer.json" laravel/framework '^11.0'
set_composer "$T/composer.json" php '^8.2'
set_composer "$T/composer.json" phpunit/phpunit '^10.5'
set_composer "$T/composer.json" fruitcake/laravel-cors REMOVE
set_composer "$T/composer.json" facade/ignition REMOVE
sed 's/\$routeMiddleware/$middlewareAliases/' "$T/app/Http/Kernel.php" > "$T/k" && mv "$T/k" "$T/app/Http/Kernel.php"
cat > "$T/phpunit.xml" <<'XML'
<?xml version="1.0" encoding="UTF-8"?>
<phpunit bootstrap="vendor/autoload.php">
    <source><include><directory suffix=".php">./app</directory></include></source>
</phpunit>
XML
expect "$T" 1 'CI pins PHP 8.1' "L11 with the runner still on PHP 8.1 fails"

# ------------------------------------------------- 7. same tree, runner fixed
T="$TMP/l11-good-runner"
build_tree "$T"
set_composer "$T/composer.json" laravel/framework '^11.0'
set_composer "$T/composer.json" php '^8.2'
set_composer "$T/composer.json" phpunit/phpunit '^10.5'
set_composer "$T/composer.json" fruitcake/laravel-cors REMOVE
set_composer "$T/composer.json" facade/ignition REMOVE
sed 's/\$routeMiddleware/$middlewareAliases/' "$T/app/Http/Kernel.php" > "$T/k" && mv "$T/k" "$T/app/Http/Kernel.php"
cat > "$T/phpunit.xml" <<'XML'
<?xml version="1.0" encoding="UTF-8"?>
<phpunit bootstrap="vendor/autoload.php">
    <source><include><directory suffix=".php">./app</directory></include></source>
</phpunit>
XML
printf "          php-version: '8.2'\n" > "$T/.github/workflows/ui-check.yml"
expect "$T" 0 "" "a fully-upgraded tree on a matching runner passes"

# ------------------------------------------- 8. reintroducing a removed helper
T="$TMP/l8-helper"
build_tree "$T"
printf '<?php\n$name = str_plural("day");\n' > "$T/app/Console/Commands/Sample.php"
expect "$T" 1 'Laravel-9-removed helper' "a reintroduced str_plural() call fails"

# ---------------------------------------------------------------------- tally
echo
echo "laravel11-check-test: ${PASS} passed, ${FAIL} failed"
if [ "$FAIL" -ne 0 ]; then
    echo "laravel11-check-test: FAILED - the readiness guard no longer catches the case(s) above"
    exit 1
fi
echo "laravel11-check-test: OK"
