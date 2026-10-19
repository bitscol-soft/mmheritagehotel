#!/usr/bin/env bash
# Laravel 11 upgrade readiness guard (BUGS #31).
#
# Two jobs:
#   1. HARD FAIL on anything that is already broken for the version declared in
#      composer.json - a Laravel-9-removed global helper, or a step-gated item
#      left behind after that step's bump landed. This is what stops a
#      half-applied upgrade from being merged.
#   2. REPORT the items that are expected to remain until the bump, plus the
#      call sites that need a human look at the step that changes their
#      semantics (Carbon 3 diffIn* signedness).
#
# The checks are version-aware: the same `$routeMiddleware` line is correct
# while composer.json says ^8 and a failure once it says ^9 or later.
set -e
cd "$(dirname "$0")/.."

fail=0
note() { printf '  %s\n' "$1"; }
bad()  { printf 'FAIL: %s\n' "$1"; fail=1; }

# Which major is declared? (8 / 9 / 10 / 11)
FRAMEWORK_MAJOR=$(node -e "try{const c=require('./composer.json');const v=String(c.require['laravel/framework']||'');const m=v.match(/(\d+)/);console.log(m?m[1]:'0')}catch(e){console.log('0')}" 2>/dev/null || echo 0)
PHPUNIT_MAJOR=$(node -e "try{const c=require('./composer.json');const v=String((c['require-dev']||{})['phpunit/phpunit']||'');const m=v.match(/(\d+)/);console.log(m?m[1]:'0')}catch(e){console.log('0')}" 2>/dev/null || echo 0)
echo "laravel11-check: composer.json declares framework ^${FRAMEWORK_MAJOR}, phpunit ^${PHPUNIT_MAJOR}"

# ---------------------------------------------------------------- 1. hard fails
echo "laravel11-check: Laravel-9-removed global helpers"
# Short generics (head/last/str_contains) are deliberately absent: str_contains
# is a PHP 8 native, data_get/data_set survive to L11, head()/last() collide
# with method names.
REMOVED="str_plural|str_singular|snake_case|camel_case|studly_case|kebab_case|title_case|str_limit|str_random|str_slug|str_start|str_finish|str_is|str_replace_first|str_replace_last|str_after|str_before|starts_with|ends_with|array_get|array_only|array_except|array_first|array_last|array_flatten|array_pluck|array_where"
HITS=$(grep -rPn "(?<![\w\$>:])(${REMOVED})\(" app module config database routes --include='*.php' 2>/dev/null | grep -v ':\s*//' | grep -v ':\s*\*' || true)
if [ -n "$HITS" ]; then
    bad "Laravel-9-removed helper call sites (use Str::/Arr:: facades):"
    echo "$HITS" | sed 's/^/  /'
else
    echo "PASS: no Laravel-9-removed global helpers"
fi

# Things that are only wrong once the corresponding bump has landed.
if [ "$FRAMEWORK_MAJOR" -ge 9 ]; then
    if grep -q '\$routeMiddleware' app/Http/Kernel.php; then
        bad "app/Http/Kernel.php still declares \$routeMiddleware, but composer.json is on ^${FRAMEWORK_MAJOR} (L9 renamed it to \$middlewareAliases - the routes would 404)"
    fi
    if grep -q 'fruitcake/laravel-cors' composer.json; then
        bad "fruitcake/laravel-cors is still required on ^${FRAMEWORK_MAJOR} (framework CORS replaced it in L9)"
    fi
    if grep -q 'facade/ignition' composer.json; then
        bad "facade/ignition is still required on ^${FRAMEWORK_MAJOR} (abandoned after L8; use spatie/laravel-ignition)"
    fi
    if grep -rq 'Fruitcake\\Cors' app config module --include='*.php' 2>/dev/null; then
        bad "Fruitcake\\Cors is still referenced in app code on ^${FRAMEWORK_MAJOR} (use Illuminate\\Http\\Middleware\\HandleCors)"
    fi
fi

# The inverse: renaming the property before the framework bump breaks L8,
# which only ever reads $routeMiddleware.
if [ "$FRAMEWORK_MAJOR" -lt 9 ] && grep -q '\$middlewareAliases' app/Http/Kernel.php; then
    bad "app/Http/Kernel.php declares \$middlewareAliases while composer.json is still ^${FRAMEWORK_MAJOR} (L8 only reads \$routeMiddleware - every route would 404)"
fi

if [ "$PHPUNIT_MAJOR" -ge 10 ]; then
    if grep -q 'processUncoveredFiles' phpunit.xml; then
        bad "phpunit.xml still uses <coverage processUncoveredFiles>, which PHPUnit ${PHPUNIT_MAJOR} rejects (tool: tools/laravel11-bump.cjs --step 2)"
    fi
fi

# The 7.3 floor is correct for Laravel 8 and wrong from the L9 step on.
if [ "$FRAMEWORK_MAJOR" -ge 9 ] && grep -q '"php": "\^7\.3' composer.json; then
    bad "composer.json declares the PHP 7.3 floor while the framework is on ^${FRAMEWORK_MAJOR} (raise it with tools/laravel11-bump.cjs)"
fi

# ---------------------------------------------------------------- 2. report
echo "laravel11-check: remaining step-gated items"
REPORTED=0
if [ "$FRAMEWORK_MAJOR" -lt 9 ] && grep -q 'protected \$routeMiddleware' app/Http/Kernel.php; then
    note "- app/Http/Kernel.php uses \$routeMiddleware (renamed at the L9 step)"; REPORTED=1
fi
if [ "$FRAMEWORK_MAJOR" -lt 9 ] && grep -q 'fruitcake/laravel-cors' composer.json; then
    note "- fruitcake/laravel-cors is required (dropped at the L9 step)"; REPORTED=1
fi
if [ "$FRAMEWORK_MAJOR" -lt 9 ] && grep -q 'facade/ignition' composer.json; then
    note "- facade/ignition is required (swapped for spatie/laravel-ignition at the L9 step)"; REPORTED=1
fi
if [ "$PHPUNIT_MAJOR" -lt 10 ] && grep -q 'processUncoveredFiles' phpunit.xml; then
    note "- phpunit.xml uses the PHPUnit 9 schema (rewritten at the L10 step)"; REPORTED=1
fi
if [ "$FRAMEWORK_MAJOR" -lt 11 ]; then
    note "- Carbon 3 arrives with L11; these diffIn*() sites need a manual sign/float check:"; REPORTED=1
    grep -rn '\->diffIn[A-Z][a-zA-Z]*(' app module --include='*.php' 2>/dev/null | sed 's/^/      /' | head -20 || true
fi
[ "$REPORTED" -eq 0 ] && note "- none: composer.json is at target"

test -f docs/LARAVEL-11-UPGRADE.md || bad "docs/LARAVEL-11-UPGRADE.md runbook is missing"

if [ "$fail" -ne 0 ]; then
    echo "laravel11-check: FAILED"
    exit 1
fi
echo "laravel11-check: OK"
