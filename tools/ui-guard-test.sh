#!/usr/bin/env bash
# =============================================================================
# tools/ui-guard-test.sh — smoke test for tools/ui-guard.sh
#
# Per docs/FRONTEND-DEV-PLAN.md W0.4. The ui-guard script is a load-bearing
# CI tripwire: if it silently regresses (e.g. an awk pattern becomes a no-op
# after a refactor) every money-math or nested-style violation will sail
# through to main. This test asserts that the three tripwires still fire
# on synthetic violations, and that benign changes still pass.
#
# Usage:
#   ./tools/ui-guard-test.sh        # run all assertions; exit 0 on success
#
# Exit codes:
#   0 — every assertion passed
#   1 — at least one assertion failed (a tripwire regressed, or a benign
#       change was incorrectly flagged)
# =============================================================================
set -u

ROOT="$(git rev-parse --show-toplevel 2>/dev/null)"
if [ -z "$ROOT" ]; then
    echo "ui-guard-test: must be run inside a git working tree" >&2
    exit 2
fi
cd "$ROOT"

if [ ! -x tools/ui-guard.sh ]; then
    echo "ui-guard-test: tools/ui-guard.sh is not executable" >&2
    exit 2
fi

# --- Test harness ------------------------------------------------------------
# Build a throwaway git repo with one Blade file. Commit a benign baseline,
# then introduce a violation, and assert that ui-guard exits 1 on the diff
# between the baseline and the violation. The temp repo is created under
# $TMPDIR (default /tmp) and torn down via an EXIT trap. Pass/fail counts
# are accumulated through a marker file because the inner work runs in
# subshells (so variables don't propagate to the parent).

MARKER="$(mktemp -u /tmp/ui-guard-test-XXXXXX.count)"
echo "0 0" > "$MARKER"
trap "rm -f '$MARKER'" EXIT

inc_pass() { awk '{ $1++; print }' "$MARKER" > "$MARKER.new" && mv "$MARKER.new" "$MARKER"; }
inc_fail() { awk '{ $2++; print }' "$MARKER" > "$MARKER.new" && mv "$MARKER.new" "$MARKER"; }

report_pass() { echo "  PASS  $1"; inc_pass; }
report_fail() { echo "  FAIL  $1"; inc_fail; }

run_violation_case() {
    # $1 = test name
    # $2 = benign baseline content (committed)
    # $3 = violation content (added to working tree + staged)
    # $4 = relative path inside the throwaway repo (resources/views/foo.blade.php)
    local name="$1" baseline="$2" violation="$3" relpath="$4"

    local tmp
    tmp="$(mktemp -d)"

    (
        cd "$tmp"
        git init -q -b main
        git config user.email "ui-guard-test@local"
        git config user.name  "ui-guard-test"

        mkdir -p "$(dirname "$relpath")"
        printf '%s' "$baseline" > "$relpath"
        git add "$relpath"
        git commit -q -m "baseline"

        printf '%s' "$violation" > "$relpath"
        git add "$relpath"

        # Run ui-guard against the staged diff. We pass --staged so the script
        # only inspects the staged changes (the synthetic violation), not
        # any pre-existing repo state.
        local rc
        "$ROOT/tools/ui-guard.sh" --staged > "$tmp/stdout" 2> "$tmp/stderr" || rc=$?
        rc="${rc:-0}"

        if [ "$rc" -eq 1 ]; then
            report_pass "$name (ui-guard exited 1 on violation)"
        else
            report_fail "$name (expected exit 1 on violation, got $rc)"
            echo "    --- stdout ---"
            sed 's/^/    /' "$tmp/stdout"
            echo "    --- stderr ---"
            sed 's/^/    /' "$tmp/stderr"
        fi
    )

    rm -rf "$tmp"
}

run_benign_case() {
    # $1 = test name
    # $2 = benign baseline content
    # $3 = benign diff content (must NOT trip the guard)
    # $4 = relative path
    local name="$1" baseline="$2" benign="$3" relpath="$4"

    local tmp
    tmp="$(mktemp -d)"

    (
        cd "$tmp"
        git init -q -b main
        git config user.email "ui-guard-test@local"
        git config user.name  "ui-guard-test"

        mkdir -p "$(dirname "$relpath")"
        printf '%s' "$baseline" > "$relpath"
        git add "$relpath"
        git commit -q -m "baseline"

        printf '%s' "$benign" > "$relpath"
        git add "$relpath"

        local rc
        "$ROOT/tools/ui-guard.sh" --staged > "$tmp/stdout" 2> "$tmp/stderr" || rc=$?
        rc="${rc:-0}"

        if [ "$rc" -eq 0 ]; then
            report_pass "$name (ui-guard exited 0 on benign change)"
        else
            report_fail "$name (expected exit 0 on benign change, got $rc)"
            echo "    --- stdout ---"
            sed 's/^/    /' "$tmp/stdout"
            echo "    --- stderr ---"
            sed 's/^/    /' "$tmp/stderr"
        fi
    )

    rm -rf "$tmp"
}

echo "ui-guard-test: smoke-testing tools/ui-guard.sh"
echo

# --- Tripwire 1: money-line --------------------------------------------------
echo "[tripwire 1] money-line change"

# Filename contains "invoice" so the path-context check fires, and the
# added line contains an amount keyword ("total" / "amount" / "due" / etc.).
# The diff is between the baseline and the violation; ui-guard should
# catch the added line as a money-line change.
run_violation_case \
    "money-line in invoice file" \
    '@section("content")' \
    '@section("content")
<h1>Invoice {{ $invoice->number }}</h1>
<p>Total: {{ $invoice->total }}</p>' \
    'resources/views/invoice/show.blade.php'

# Path does NOT contain any context word, but the LINE itself contains
# "voucher" + "amount" — exercises the line-context fallback in the
# tripwire awk. The file name is intentionally generic to prove that
# the tripwire does not depend on path matching alone.
run_violation_case \
    "money-line via line-context fallback (voucher + amount)" \
    '@section("content")' \
    '@section("content")
<h1>Record</h1>
<p>Voucher amount: {{ $voucher->amount }}</p>' \
    'resources/views/some/random/path.blade.php'

# Amount keyword adjacent to a non-word character (curly brace, paren,
# comma, semicolon) — exercises the portable character-class boundaries
# the previous (mawk-broken) `\<amount\>` regex used to handle.
run_violation_case \
    "money-line amount with non-word boundary" \
    '@section("content")' \
    '@section("content")
<p>Receipt total: ({{ $r->amount }})</p>' \
    'resources/views/receipt/show.blade.php'

# Per-line override marker should suppress the warning when placed on
# the same line as the violation. This is the positive case for the
# override machinery — the override is per-LINE, not per-block (the
# block-level override is {{-- money-travel-on-block: --}}...{{-- money-travel-on-end --}}).
run_benign_case \
    "money-line with per-line override" \
    '@section("content")' \
    '@section("content")
<h1>Invoice {{ $invoice->number }}</h1>
<p>Total: {{ $invoice->total }} <!-- money-travel-on: legacy total kept for backward compat --></p>' \
    'resources/views/invoice/show.blade.php'

# Per-line override with an empty + line in the diff hunk. The
# original awk regex (^\+[^+]) did not match the bare "+" line
# (just a + followed by a newline, no second char), so the
# new_line counter lagged the file line number by 1. This caused
# the per-line override on the file-line-after-the-blank to
# miss (override marker recorded on file-line N, awk comparing
# against new_line = N-1). The fix replaces the regex with /^\+/
# (gated by an explicit in_hunk flag so the "+++ b/file" file
# header is still skipped) and increments new_line for every +
# line — including the empty one. This test was added when the
# off-by-one was first observed (W3.1 closeout 4a4c2726) and
# worked around with a block-level override; it now serves as
# the regression guard for the per-line case.
run_benign_case \
    "per-line override with empty + line in hunk (regression for off-by-one)" \
    '@section("content")' \
    '@section("content")

<p>Total: {{ $invoice->total }} <!-- money-travel-on: legacy total kept for backward compat --></p>' \
    'resources/views/invoice/show.blade.php'

# --- Tripwire 2: nested style include ----------------------------------------
echo
echo "[tripwire 2] nested @include inside <style>"

run_violation_case \
    "nested @include inside <style>" \
    '<style>body { color: red; }</style>' \
    '<style>
    body { color: red; }
    @include("partials.some-style-block")
</style>' \
    'resources/views/welcome.blade.php'

# --- Tripwire 3: page-header + x-page combo ---------------------------------
echo
echo "[tripwire 3] page-header section + x-mm.page combo"

run_violation_case \
    "x-mm.page + @section('page-header') combo" \
    '<x-mm.page title="Hello"></x-mm.page>' \
    '@section("page-header")
    <i class="fa fa-info"></i> Hello
@stop
<x-mm.page title="Hello"></x-mm.page>' \
    'resources/views/hello.blade.php'

# --- Negative: a fully benign change must pass --------------------------------
echo
echo "[negative] benign change must NOT trip"

run_benign_case \
    "benign text change" \
    '<h1>Hello</h1>' \
    '<h1>Hello, world</h1>' \
    'resources/views/welcome.blade.php'

run_benign_case \
    "benign css change" \
    '<style>body { color: red; }</style>' \
    '<style>body { color: blue; }</style>' \
    'resources/views/welcome.blade.php'

# A line that contains the substring "amount" inside a larger word
# (e.g. "paramount", "reamounted") must NOT trip — the portable
# character-class word boundary rejects these. This is the case
# that the old `\<amount\>` regex would have ALSO rejected (because
# GNU awk has the same word-boundary semantic), but the new portable
# regex has to be deliberately equivalent. Without this assertion
# a future refactor that broke the boundary check could regress
# into matching "paramount" as money math.
run_benign_case \
    "amount as substring of larger word (must not trip)" \
    '@section("content")
<p>The paramount concern is user trust.</p>' \
    '@section("content")
<p>The paramount concern is user trust.</p>
<p>It is a paramount priority, not a minor one.</p>' \
    'resources/views/welcome.blade.php'

# --- Summary -----------------------------------------------------------------
echo
read -r pass fail < "$MARKER"
total=$((pass + fail))
echo "ui-guard-test: $pass/$total assertions passed"
if [ "$fail" -ne 0 ]; then
    echo "ui-guard-test: FAILED — at least one tripwire regressed or a benign change was incorrectly flagged." >&2
    exit 1
fi
echo "ui-guard-test: all tripwires are firing correctly."
exit 0
