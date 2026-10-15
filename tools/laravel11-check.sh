#!/usr/bin/env bash
# Laravel 11 upgrade readiness guard (BUGS #31, step 0).
#
# Fails when a global helper REMOVED in Laravel 9 is reintroduced anywhere in
# the app/module trees (the framework's own guard in those versions is a
# fatal). Prints the step-gated structural items that are EXPECTED to remain
# until the composer bumps in docs/LARAVEL-11-UPGRADE.md land.
set -e
cd "$(dirname "$0")/.."

echo "laravel11-check: scanning for Laravel-9-removed global helpers"

# Laravel 9 removed these global helpers (Str/Arr facades replace them).
# Short generics (head/last/str_contains) are deliberately absent: str_contains
# is a PHP 8 native, data_get/data_set survive to L11, head()/last() collide
# with method names.
REMOVED="str_plural|str_singular|snake_case|camel_case|studly_case|kebab_case|title_case|str_limit|str_random|str_slug|str_start|str_finish|str_is|str_replace_first|str_replace_last|str_after|str_before|starts_with|ends_with|array_get|array_only|array_except|array_first|array_last|array_flatten|array_pluck|array_where"
HITS=$(grep -rPn "(?<![\w\$>:])(${REMOVED})\(" app module config database routes --include='*.php' 2>/dev/null | grep -v ':\s*//' | grep -v ':\s*\*' || true)
if [ -n "$HITS" ]; then
    echo "FAIL: Laravel-9-removed helper call sites found (use Str::/Arr:: facades):"
    echo "$HITS"
    exit 1
fi
echo "PASS: no Laravel-9-removed global helpers"

echo "laravel11-check: step-gated items (expected until the composer bumps land)"
if grep -q 'protected \$routeMiddleware' app/Http/Kernel.php; then
    echo "  - app/Http/Kernel.php still uses \$routeMiddleware (rename to \$middlewareAliases at the L9 bump)"
fi
if grep -q 'fruitcake/laravel-cors' composer.json; then
    echo "  - fruitcake/laravel-cors still required (drop at the L9 bump; CORS is in-framework)"
fi
if grep -q 'facade/ignition' composer.json; then
    echo "  - facade/ignition still required (swap for spatie/laravel-ignition at the L9 bump)"
fi
if grep -q '"php": "\^7.3' composer.json; then
    echo "  - composer.json php floor is still 7.3-era (raise to ^8.0.2/^8.2 with the bumps)"
fi

test -f docs/LARAVEL-11-UPGRADE.md
echo "PASS: docs/LARAVEL-11-UPGRADE.md runbook present"

echo "laravel11-check: OK"
