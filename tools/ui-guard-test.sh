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

# REMOVED money-line in an invoice file must NOT trip. The W6 rule
# is "no money-math changes"; removing the math is the GOOD direction
# and should be celebrated, not flagged. This is the regression test
# for the additions-only conversion. The replacement line intentionally
# contains no amount keyword (no "total", "amount", "paid", etc.) so
# that the tripwire is unambiguously testing the additions-only logic.
run_benign_case \
    "removed money-line in invoice file (additions-only, must not trip)" \
    '<h1>Invoice {{ $invoice->number }}</h1>
<p>Total: {{ $invoice->total }}</p>' \
    '<h1>Invoice {{ $invoice->number }}</h1>
<p>See itemized bill below.</p>' \
    'resources/views/invoice/show.blade.php'

# A diff that BOTH adds a money-line AND removes a money-line in
# the same file should trip on the addition and ignore the removal.
# Same file, same hunk: the + side carries the violation, the -
# side is the cleanup.
run_violation_case \
    "added + removed money-line in the same hunk (only addition trips)" \
    '<h1>Invoice {{ $invoice->number }}</h1>
<p>Total: {{ $invoice->total }}</p>' \
    '<h1>Invoice {{ $invoice->number }}</h1>
<p>Old total removed.</p>
<p>New due: {{ $invoice->due }}</p>' \
    'resources/views/invoice/show.blade.php'

# A diff that ONLY removes a money-line (no addition) must NOT
# trip. This is the cleanest pure-cleanup case: legacy total gone,
# nothing added, no violation.
run_benign_case \
    "pure money-line cleanup (removal only, no addition, must not trip)" \
    '<h1>Receipt</h1>
<p>Receipt amount: {{ $r->amount }}</p>' \
    '<h1>Receipt</h1>
<p>See itemized bill below.</p>' \
    'resources/views/receipt/show.blade.php'

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

# A file that ALREADY has the round-10 defect (an @include inside
# a <style> block) and only changes a non-style line elsewhere in
# the file must NOT trip. This is the diff-based regression test:
# the original file-content tripwire 2 fired on every commit for
# every pre-migration file with the defect, producing noise. The
# diff-based check only flags + lines that ADD a directive inside
# a <style> block.
run_benign_case \
    "pre-existing @include in <style> not on the changed line (must not trip)" \
    '<style>
    body { color: red; }
    @include("partials.some-style-block")
</style>
<h1>Unrelated heading</h1>' \
    '<style>
    body { color: red; }
    @include("partials.some-style-block")
</style>
<h1>Updated heading</h1>' \
    'resources/views/welcome.blade.php'

# A file that has NO <style> block in the change but adds a
# completely unrelated line must not trip. The state machine
# is_gating on in_style; a + line outside any <style> is benign.
run_benign_case \
    "unrelated change in a file with no <style> block (must not trip)" \
    '<h1>Old</h1>' \
    '<h1>New</h1>
<p>Updated text.</p>' \
    'resources/views/welcome.blade.php'

# Removing a @directive from inside a <style> block is the GOOD
# direction and must NOT trip. The diff-based check is
# additions-only, matching tripwire 1 (money-line) and tripwire 6
# (number_format).
run_benign_case \
    "removed @include from inside <style> (must not trip, additions-only)" \
    '<style>
    body { color: red; }
    @include("partials.some-style-block")
</style>' \
    '<style>
    body { color: red; }
</style>' \
    'resources/views/welcome.blade.php'

# A diff that BOTH adds a <style> block AND a @directive on a
# later + line inside that new block is the canonical violation.
# This is the same as the existing test, but stated as a separate
# case to document the additions-only contract.
run_violation_case \
    "added <style> + @include in same hunk (canonical violation)" \
    '<h1>Hello</h1>' \
    '<h1>Hello</h1>
<style>
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

# The diff-based check is the main win over the legacy file-content
# tripwire. A pre-migration file (one that already has BOTH markers
# sitting in the working tree from the W2.3 work-in-progress) must
# NOT trip when an unrelated commit changes something else in the
# file. The old file-content tripwire fired on every commit for every
# such file; the new diff-based check ignores diffs that don't add a
# new marker instance.
run_benign_case \
    "unrelated change in a file that already has both markers" \
    '@section("page-header")
    <i class="fa fa-info"></i> Hello
@stop
<x-mm.page title="Hello"></x-mm.page>' \
    '@section("page-header")
    <i class="fa fa-info"></i> Hello
@stop
<x-mm.page title="Hello"></x-mm.page>
<p>An unrelated paragraph added by a commit.</p>' \
    'resources/views/hello.blade.php'

# The migration direction (removing @section('page-header') from a
# file that still has <x-mm.page>) must NOT trip. The diff does not
# ADD a marker; the + lines are just the unchanged <x-mm.page> and
# maybe some other content. The tripwire only flags additions.
run_benign_case \
    "migration direction (remove @section('page-header') from x-mm.page file)" \
    '@section("page-header")
    <i class="fa fa-info"></i> Hello
@stop
<x-mm.page title="Hello"></x-mm.page>' \
    '<x-mm.page title="Hello"></x-mm.page>' \
    'resources/views/hello.blade.php'

# --- Negative: a fully benign change must pass --------------------------------
echo
echo "[negative] benign change must NOT trip"

run_benign_case \
    "benign text change" \
    '<h1>Hello</h1>' \
    '<h1>Hello, world</h1>' \
    'resources/views/welcome.blade.php'

# Editing a CSS partial (_css/*.blade.php) is the right place to
# add or change styles. The plan says "New CSS only in tokens.css
# / component partials" — so a benign change to a CSS partial
# must NOT trip any of the tripwires. (This replaces an older
# "benign css change" test that edited a non-CSS Blade view; that
# scenario is now itself a violation under tripwire 4, and a
# regression test for tripwire 4 below covers the violation
# case in the other direction.)
run_benign_case \
    "benign change inside a _css/*.blade.php partial" \
    '<style>body { color: red; }</style>' \
    '<style>body { color: blue; }</style>' \
    'resources/views/welcome/_css/page.blade.php'

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

# --- Tripwire 4: new <style> tag in a non-CSS Blade file ---------------------
echo
echo "[tripwire 4] new <style> tag in a non-CSS Blade file"

# A new <style> block in a regular Blade view (not a _css partial)
# is a violation of the §0 guardrail: "New CSS only in tokens.css /
# component partials; per-page <style> shrinks, never grows." The
# tripwire fires on the opening <style> tag of the new block.
run_violation_case \
    "new <style> block in a non-CSS Blade view" \
    '<h1>Hello</h1>' \
    '<h1>Hello</h1>
<style>
body { color: red; }
</style>' \
    'resources/views/welcome.blade.php'

# A new <style> block in a _css/*.blade.php partial is the
# RIGHT place to add CSS — it must NOT trip the guard. The
# tripwire's path filter exempts any file whose path contains
# `_css/.../*.blade.php`.
run_benign_case \
    "new <style> block in a _css/*.blade.php partial" \
    '<style>body { color: red; }</style>' \
    '<style>
body { color: red; }
.btn { padding: 4px; }
</style>' \
    'resources/views/welcome/_css/page.blade.php'

# An inline `style="..."` attribute on an element is NOT a
# <style> tag — it is a per-element style attribute, which the
# guard does not police. The regex `<style[> \t/]` requires
# the char after `<style` to be a tag terminator; the char
# after `<style="` is `"`, which is not a terminator, so the
# tripwire does not fire.
run_benign_case \
    "inline style=\"…\" attribute (must not trip)" \
    '<h1>Hello</h1>' \
    '<h1 style="color: red;">Hello</h1>' \
    'resources/views/welcome.blade.php'

# A <link rel="stylesheet" href="…"> is a link to an external
# stylesheet, not a <style> tag. The char after `<style` is
# `s` (the start of `stylesheet`), which is not a tag
# terminator, so the regex does not match. This test
# specifically guards against a future refactor that breaks
# the `[> \t/]` terminator check and starts matching
# `<link rel="stylesheet">` as a violation.
run_benign_case \
    "<link rel=\"stylesheet\"> (must not trip)" \
    '<h1>Hello</h1>' \
    '<h1>Hello</h1>
<link rel="stylesheet" href="/css/tokens.css">' \
    'resources/views/welcome.blade.php'

# --- Tripwire 5: x-mm.field misuse -------------------------------------------
echo
echo "[tripwire 5] x-mm.field misuse (W4.3b rules)"

# Sub-check 1 (form page): the W4.3b replacement pattern. A
# developer replaces a raw input-group (with the currency addon)
# with a <x-mm.field>. The x-mm.field component cannot render
# the addon, so the currency symbol disappears from the rendered
# form. The diff has BOTH a - line removing the input-group AND
# a + line adding <x-mm.field>. The tripwire fires on the +
# line.
run_violation_case \
    "x-mm.field replaces a raw input-group (W4.3b replacement pattern)" \
    '<h1>Create</h1>
<div class="input-group">
    <span class="input-group-addon">$</span>
    <input type="text" name="total" class="form-control">
</div>' \
    '<h1>Create</h1>
<x-mm.field id="total" name="total" value="" label="Total" />' \
    'resources/views/sale/sales/create.blade.php'

# Sub-check 1 (form page) - chosen-select variant: the W4.3b rule
# also covers JS-hooked select inputs (chosen-select class), which
# x-mm.field would replace with a vanilla input that loses the
# chosen jQuery hook.
run_violation_case \
    "x-mm.field replaces a chosen-select (W4.3b replacement pattern)" \
    '<h1>Create</h1>
<select name="supplier" class="chosen-select form-control">
    <option>One</option>
</select>' \
    '<h1>Create</h1>
<x-mm.field id="supplier" name="supplier" value="" label="Supplier" />' \
    'resources/views/sale/sales/create.blade.php'

# Sub-check 1 benign: x-mm.field on a form page in a hunk that
# does NOT have input-group or chosen-select. The standard pattern.
run_benign_case \
    "x-mm.field on a form page with no input-group / chosen-select" \
    '<h1>Create</h1>' \
    '<h1>Create</h1>
<x-mm.field id="name" name="name" value="" label="Name" />
<x-mm.field id="email" name="email" value="" label="Email" />' \
    'resources/views/sale/sales/create.blade.php'

# Sub-check 1 scope: a non-form page (index.blade.php) is OUT OF
# SCOPE for sub-check 1. Even if the hunk has a - line removing
# an input-group AND a + line adding <x-mm.field>, the tripwire
# does not fire on non-form pages. The sub-check is gated on the
# file path. This test deliberately exercises the same diff
# pattern that would trip sub-check 1 on a form page.
run_benign_case \
    "x-mm.field replacing input-group on a NON-form page (out of scope for sub-check 1)" \
    '<h1>List</h1>
<div class="input-group">
    <input type="text" name="filter" class="form-control">
</div>' \
    '<h1>List</h1>
<x-mm.field id="filter" name="filter" value="" label="Filter" />' \
    'resources/views/sale/sales/index.blade.php'

# Sub-check 2: <x-mm.field> with name= but no id= on the same
# line. The x-mm.field component requires id= for the <label
# for=...> and the input id=... attributes. Without id=, the
# rendered HTML is broken (empty for and id). Sub-check 2 is
# gated on form pages (same as sub-check 1), so the violation
# file path is a *create.blade.php — a non-form page use
# (e.g. a filter input on an index page) is out of scope and
# the developer can add id= when they add a <label>.
run_violation_case \
    "x-mm.field has name= but no id= on the same line (form page)" \
    '<h1>Create</h1>' \
    '<h1>Create</h1>
<x-mm.field name="email" value="" label="Email" />' \
    'resources/views/sale/sales/create.blade.php'

# Sub-check 2 benign: <x-mm.field> with both id= and name= on
# the same line. The standard pattern.
run_benign_case \
    "x-mm.field with both id= and name= (must not trip, form page)" \
    '<h1>Create</h1>' \
    '<h1>Create</h1>
<x-mm.field id="email" name="email" value="" label="Email" />' \
    'resources/views/sale/sales/create.blade.php'

# Sub-check 2 scope: a non-form page (index.blade.php) is OUT
# OF SCOPE for sub-check 2 — the same form-page gate that
# applies to sub-check 1. A missing id= on a non-form use of
# x-mm.field is not a W4.3b violation; the developer can add
# id= when they add a <label>. This test deliberately
# exercises the same diff pattern that would trip sub-check 2
# on a form page.
run_benign_case \
    "x-mm.field missing id= on a NON-form page (out of scope for sub-check 2)" \
    '<h1>List</h1>' \
    '<h1>List</h1>
<x-mm.field name="filter" value="" label="Filter" />' \
    'resources/views/sale/sales/index.blade.php'

# --- Tripwire 6: number_format(...) addition in money context ---------------
echo
echo "[tripwire 6] number_format(...) addition in money context"

# The classic case: a renamed column that the keyword-based
# tripwire 1 misses because the new column name (camelCase
# "grandTotal") does not match the (^|[^a-z0-9_])(total) word
# boundary. A number_format(...) call on this column in an
# invoice file is exactly the kind of money-math change that
# the W6 guardrail wants to surface.
run_violation_case \
    "number_format on a renamed (camelCase) column in an invoice file" \
    '<h1>Invoice</h1>' \
    '<h1>Invoice</h1>
<td>{{ number_format($invoice->grandTotal, 2) }}</td>' \
    'resources/views/invoice/show.blade.php'

# The line-context fallback: a number_format call on a
# non-invoice file but a line that contains a money-context
# word (e.g. "voucher"). This is the same fallback pattern
# that tripwire 1 uses for the keyword check.
run_violation_case \
    "number_format on a renamed column (line-context fallback)" \
    '<h1>Record</h1>' \
    '<h1>Record</h1>
<td>Voucher subtotal: {{ number_format($voucher->subtotal, 2) }}</td>' \
    'resources/views/some/random/path.blade.php'

# A number_format call that is NOT in money context (generic
# file, no money-context word on the line) must NOT trip.
# This is the negative case — the tripwire requires the
# context check to fire.
run_benign_case \
    "number_format in a non-money file with no money-context word" \
    '<h1>About</h1>' \
    '<h1>About</h1>
<p>{{ number_format($count, 2) }} items</p>' \
    'resources/views/welcome.blade.php'

# The per-line money-travel-on override should suppress the
# warning when placed on the same line as the violation. The
# override machinery is shared with tripwire 1 — same marker
# comment, same parsing logic.
run_benign_case \
    "number_format with per-line money-travel-on override" \
    '<h1>Invoice</h1>' \
    '<h1>Invoice</h1>
<td>{{ number_format($invoice->grandTotal, 2) }} <!-- money-travel-on: legacy total kept for backward compat --></td>' \
    'resources/views/invoice/show.blade.php'

# A number_format call with a non-word boundary keyword
# (e.g. "paramount") is NOT a money context — the line-context
# check uses the same (^|[^a-z0-9_]) boundaries that the
# tripwire 1 has_amount check uses, so "paramount" does NOT
# match. This is a regression test for the boundary logic
# shared between the two tripwires.
run_benign_case \
    "number_format on a 'paramount' line (must not trip)" \
    '<h1>About</h1>' \
    '<h1>About</h1>
<p>The paramount concern is user trust.</p>
<p>Count: {{ number_format($count, 2) }}</p>' \
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
