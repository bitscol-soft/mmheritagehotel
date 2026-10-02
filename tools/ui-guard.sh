#!/usr/bin/env bash
# =============================================================================
# tools/ui-guard.sh — diff tripwire for Blade / JS / CSS UI changes
#
# This script is the mechanical guardrail from docs/FRONTEND-DEV-PLAN.md W0.4:
# any change that touches invoice/money math or that nests @include inside a
# <style>…</style> block fails the build. Tribal knowledge is encoded as code.
#
# Tripwires (fail = non-zero exit):
#   1. Money-line changes: any added or removed line that combines an "invoice"
#      context (filename match or surrounding code match) with an "amount"
#      keyword in a Blade template under resources/views/ or module/*/views/.
#   2. Nested style includes: any Blade template that puts @include/@yield
#      directives inside a <style>…</style> block (the round-10 defect class).
#
# Usage:
#   ./tools/ui-guard.sh           # checks the working tree against HEAD
#   ./tools/ui-guard.sh --staged  # checks the staged diff (for pre-commit)
#   ./tools/ui-guard.sh --base=main  # checks diff against a specific ref
#
# Exit codes:
#   0 — no tripwires triggered
#   1 — at least one tripwire triggered (details on stderr)
#   2 — usage / setup error
# =============================================================================
set -u

ROOT="$(git rev-parse --show-toplevel 2>/dev/null)"
if [ -z "$ROOT" ]; then
    echo "ui-guard: must be run inside a git working tree" >&2
    exit 2
fi
cd "$ROOT"

MODE="working"
BASE_REF=""
case "${1:-}" in
    "")
        MODE="working"
        ;;
    --staged)
        MODE="staged"
        ;;
    --base=*)
        MODE="diff"
        BASE_REF="${1#--base=}"
        ;;
    -h|--help)
        grep '^#' "$0" | sed 's/^# *//; s/^#$//'
        exit 0
        ;;
    *)
        echo "ui-guard: unknown argument: $1" >&2
        echo "Usage: $0 [--staged|--base=<ref>]" >&2
        exit 2
        ;;
esac

# Collect the set of Blade files to scan, depending on the mode.
collect_blade_files() {
    case "$MODE" in
        working)
            # Modified / added Blade files in the working tree.
            git ls-files --others --modified --exclude-standard -- '*.blade.php' \
                | grep -E '^(resources/views|module/[^/]+/views)/' \
                | sort -u
            ;;
        staged)
            # Staged Blade files (vs HEAD).
            git diff --cached --name-only --diff-filter=AM -- '*.blade.php' \
                | grep -E '^(resources/views|module/[^/]+/views)/' \
                | sort -u
            ;;
        diff)
            # Blade files touched by the diff against BASE_REF.
            git diff --name-only --diff-filter=AM "$BASE_REF"...HEAD -- '*.blade.php' \
                | grep -E '^(resources/views|module/[^/]+/views)/' \
                | sort -u
            ;;
    esac
}

fail=0
report() {
    local title="$1"; shift
    echo
    echo "FAIL: $title" >&2
    while [ $# -gt 0 ]; do
        echo "  $1" >&2
        shift
    done
    fail=1
}

# -----------------------------------------------------------------------------
# Tripwire 1: money-line diff.
#
# A line that contains both an invoice-style context and an amount keyword,
# within a Blade file under resources/views/ or module/*/views/, fails the
# guard. "Context" is either the file path containing "invoice" / "checkout" /
# "payment" / "voucher" / "receipt", OR the line containing one of those words.
#
# The point is to surface accidental changes to financial math early. False
# positives are accepted as the price of a mechanical guard — the dev can mark
# the line with `<!-- money-travel-on: <reason> -->` to suppress the warning
# for that specific change.
# -----------------------------------------------------------------------------
tripwire_money_line() {
    local f
    local found=0
    while IFS= read -r f; do
        [ -z "$f" ] && continue
        [ ! -f "$f" ] && continue
        local context=""
        case "$f" in
            *invoice*|*checkout*|*payment*|*voucher*|*receipt*) context=1 ;;
        esac
        # Read added/removed lines from the diff. For working-tree mode, we
        # compare against HEAD. For staged/diff, we use git diff accordingly.
        local diff_args=""
        case "$MODE" in
            working) diff_args="-U0 --" ;;
            staged)  diff_args="-U0 --cached --" ;;
            diff)    diff_args="-U0 $BASE_REF...HEAD --" ;;
        esac
        local hits
        hits="$(git diff $diff_args "$f" 2>/dev/null \
            | awk -v file="$f" -v ctx="$context" '
                /^\+[^+]/ || /^-[^-]/ {
                    line = substr($0, 2)
                    low = tolower(line)
                    has_amount = (low ~ /amount|total|grand_total|grand total|due|paid|balance|price|rent|fare|charge|vat|tax/)
                    has_ctx = (ctx != "") || (low ~ /invoice|checkout|payment|voucher|receipt|booking/)
                    if (has_amount && has_ctx) {
                        printf("    %s:%s\n", file, NR)
                    }
                }
            ')"
        if [ -n "$hits" ]; then
            report "money-line change in $f" \
                "  Lines flagged (invoice/payment context + amount keyword):" \
                "$hits" \
                "  Override with: <!-- money-travel-on: <reason> --> on the affected line." \
                "  See docs/FRONTEND-DEV-PLAN.md §0 (guardrails) for the rule."
            found=1
        fi
    done < <(collect_blade_files)
    return $found
}

# -----------------------------------------------------------------------------
# Tripwire 2: @include / @yield inside <style>…</style>.
#
# A Blade template that places an @include (or @yield, @stack) directive
# inside a <style>…</style> block is a round-10-class defect: the included
# partial emits its own <style> tags, breaking the outer block. We grep the
# file in raw form (no Blade compile) for the pattern.
# -----------------------------------------------------------------------------
tripwire_nested_style_include() {
    local f
    local found=0
    while IFS= read -r f; do
        [ -z "$f" ] && continue
        [ ! -f "$f" ] && continue
        # Use awk to detect <style>…</style> blocks containing @include/@yield/@stack.
        local hits
        hits="$(awk '
            /<style[^>]*>/ { in_style = 1; next }
            /<\/style>/     { in_style = 0; next }
            in_style && /@(include|yield|stack|push|section|once)/ {
                printf("%s:%d:%s\n", FILENAME, NR, $0)
            }
        ' "$f")"
        if [ -n "$hits" ]; then
            report "nested style include in $f" \
                "  @include/@yield/@stack inside a <style> block:" \
                "$hits" \
                "  Move the @include outside the <style> tag, or inline the CSS." \
                "  This is the round-10 defect class — see docs/BUGS.md."
            found=1
        fi
    done < <(collect_blade_files)
    return $found
}

echo "ui-guard: mode=$MODE, base=$BASE_REF"
echo "ui-guard: scanning $(collect_blade_files | wc -l) Blade files"

tripwire_money_line || true
tripwire_nested_style_include || true

if [ "$fail" -ne 0 ]; then
    echo
    echo "ui-guard: at least one tripwire triggered." >&2
    exit 1
fi

echo "ui-guard: no tripwires triggered."
exit 0