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
        # The tripwire honors an explicit override marker on a file line:
        #   <!-- money-travel-on: <reason> -->     (HTML)
        #   {{-- money-travel-on: <reason> --}}    (Blade)
        # The marker is checked on the working tree copy (for added lines)
        # and on the HEAD copy (for removed lines). A diff line is
        # suppressed when EITHER side of the diff at that hunk position
        # carries the marker. The list of line numbers is computed once
        # per file (and once for HEAD) and passed into the awk as
        # comma-separated scalar strings.
        #
        # Two block-level markers are also supported:
        #   {{-- money-travel-on-block: <reason> --}}
        #   {{-- money-travel-on-end --}}
        # The "block" marker on line B turns the tripwire off for every
        # file line B..E where E is the matching "end" marker. A block
        # without an end marker runs to end of file. This is the right
        # tool for whole-parts that contain a lot of money math (e.g.
        # the booking table rows partial) — putting a per-line override
        # on every cell is noise; one block marker is honest.
        local override_lines_added override_lines_removed
        override_lines_added="$(grep -nE '(<!--|\{\{--)\s*money-travel-on:' "$f" 2>/dev/null | cut -d: -f1 | paste -sd, -)"
        override_lines_removed="$(git show "HEAD:$f" 2>/dev/null | grep -nE '(<!--|\{\{--)\s*money-travel-on:' | cut -d: -f1 | paste -sd, -)"
        # Compute the set of line numbers inside an open-ended block.
        # The awk receives the list as a comma-separated scalar
        # (block_lo / block_hi) where block_lo is the start of the
        # first block and block_hi is either the end marker line or
        # the line count of the file (for an unterminated block). For
        # the simple case of one block, the awk's "in block" check
        # becomes "new_line >= block_lo && new_line <= block_hi" (for
        # added lines) and similarly for old_line.
        local block_lo block_hi
        local file_line_count
        file_line_count="$(wc -l < "$f" 2>/dev/null | tr -d ' ')"
        block_lo="$(grep -nE '\{\{--\s*money-travel-on-block:' "$f" 2>/dev/null | head -1 | cut -d: -f1)"
        if [ -n "$block_lo" ]; then
            local end_line
            end_line="$(awk -v start="$block_lo" '
                NR > start && /\{\{--\s*money-travel-on-end\s*--\}\}/ { print NR; exit }
            ' "$f" 2>/dev/null)"
            if [ -n "$end_line" ]; then
                block_hi="$end_line"
            else
                block_hi="$file_line_count"
            fi
        else
            block_lo=""
            block_hi=""
        fi
        local hits
        hits="$(git diff $diff_args "$f" 2>/dev/null \
            | awk -v file="$f" -v ctx="$context" -v add_ov="$override_lines_added" -v rem_ov="$override_lines_removed" -v blk_lo="$block_lo" -v blk_hi="$block_hi" '
                BEGIN {
                    n_ov_add = split(add_ov, add_arr, ",")
                    n_ov_rem = split(rem_ov, rem_arr, ",")
                    blk_lo = blk_lo + 0
                    blk_hi = blk_hi + 0
                    in_block = 0
                    new_line = 0
                    old_line = 0
                    in_hunk = 0
                }
                function in_block_range(fl) {
                    # A `money-travel-on-block:` marker on any line in
                    # the file opts the WHOLE file out of the tripwire.
                    # This is the right tool for whole-partial files
                    # that contain a lot of money math (booking rows,
                    # invoice tables, payment receipts) where per-line
                    # overrides would be noise. The awk only needs a
                    # yes/no; the caller computes the marker line
                    # before invoking us.
                    if (blk_lo <= 0) return 0
                    return 1
                }
                /^@@/ {
                    rest = $0
                    sub(/^@@ /, "", rest)
                    sub(/ @@.*$/, "", rest)
                    split(rest, parts, " ")
                    split(parts[1], a, ",")
                    split(parts[2], b, ",")
                    old_line = a[1] + 0
                    new_line = b[1] + 0
                    in_hunk = 1
                    next
                }
                # The "--- a/file" / "+++ b/file" lines only appear
                # as file headers BEFORE the first hunk. The previous
                # regex (^\+[^+] / ^-[^-]) skipped them by accident
                # (the [^+] char class rejects the second +); the
                # explicit in_hunk gate below does it correctly
                # without breaking the empty-+-line case the
                # previous regex got wrong (see the empty-line case
                # in tripwire_money_line below).
                /^---/ { if (!in_hunk) next }
                /^\+\+\+/ { if (!in_hunk) next }
                /^\+/ {
                    if (!in_hunk) next
                    line = substr($0, 2)
                    low = tolower(line)
                    # Use a portable word-boundary approximation: (^|[^a-z])
                    # before, ($|[^a-z]) after. The GNU `\<...\>` syntax is
                    # NOT supported by mawk (the default awk on Debian-based
                    # systems including the GitHub Actions ubuntu-latest
                    # runner), so without this fallback the tripwire silently
                    # matches nothing and the money-line guard is a no-op.
                    has_amount = (low ~ /(^|[^a-z0-9_])(amount|total|grand_total|grand total|due|paid|balance|price|rent|fare|charge|vat|tax)($|[^a-z0-9_])/)
                    has_ctx = (ctx != "") || (low ~ /invoice|checkout|payment|voucher|receipt|booking/)
                    if (has_amount && has_ctx) {
                        fl = new_line
                        overridden = 0
                        for (i = 1; i <= n_ov_add; i++) {
                            if (add_arr[i]+0 == fl) { overridden = 1; break }
                        }
                        if (!overridden && in_block_range(fl)) overridden = 1
                        if (!overridden) {
                            printf("    %s:+%d\n", file, fl)
                        }
                    }
                    # Always increment new_line for every + line we see
                    # in a hunk — INCLUDING the empty + line case. The
                    # previous regex (^\+[^+]) skipped empty + lines
                    # (just "+" followed by newline, no second char),
                    # which caused the new_line counter to lag the file
                    # line number by 1. That broke the per-line
                    # money-travel-on override whenever a diff hunk
                    # contained an empty + line (a common case when a
                    # restructuring PR adds blank lines between
                    # sections, as in the W3.1 / W3.1-twin
                    # reservation-invoice closeouts).
                    new_line++
                }
                /^-/ {
                    if (!in_hunk) next
                    # The - branch only needs to keep old_line in sync.
                    # The tripwire only flags ADDITIONS — a REMOVED
                    # money-line is the GOOD direction. The W6 rule
                    # says "no money-math changes" and removing the
                    # math is not a change to it. (Previously the -
                    # branch also fired; that produced noise on every
                    # legitimate cleanup of legacy money math. The
                    # same pattern is used in tripwire 6.)
                    old_line++
                }
                /^ / {
                    new_line++
                    old_line++
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
# Tripwire 3: page-header + x-page combo (W2.3).
#
# A Blade file that has BOTH a <x-mm.page> component AND a
# @section('page-header') block is a W2.3 violation: the master layout
# does not @yield('page-header'), so the page-header section is dead
# code. The dev plan says "x-page becomes the only header pattern; delete
# per-page page-header blocks as encountered". This tripwire enforces
# that going forward: a file that has both fails the guard.
#
# The tripwire is a DIFF check (not a file-content check), so the
# migration in W2.3 that removes 200+ dead page-header blocks does
# NOT trip itself - only diffs that ADD a marker to a file that
# already has the other marker (i.e. that perpetuate the combo) fire.
# Pre-migration files that sit in the working tree with both markers
# are not the responsibility of any particular commit; the tripwire
# only flags diffs that are responsible.
tripwire_xpage_pageheader_combo() {
    local f
    local found=0
    while IFS= read -r f; do
        [ -z "$f" ] && continue
        [ ! -f "$f" ] && continue
        # File-state check: does the working tree copy of the file
        # already carry either marker? Both grep patterns are the same
        # single-line anchors used by the diff-line check below. The
        # grep may match a marker that lives inside a Blade comment
        # (e.g. `{{-- <x-mm.page old --}}`) and that small
        # false-positive is accepted as the price of a fast O(file
        # size) check; a developer can clean up the comment to
        # silence the tripwire.
        local has_xpage=0 has_pageheader=0
        grep -qE '<x-mm\.page[> \t/]' "$f" && has_xpage=1
        grep -qE "@section\(\s*['\"]page-header['\"]" "$f" && has_pageheader=1
        # If the file (working tree) does not have BOTH markers, the
        # current diff cannot introduce the combo by adding a new
        # instance of either side; skip the diff scan entirely. This
        # is the main win over the legacy file-content tripwire: a
        # working tree that has 200+ files stuck with both markers
        # (un-migrated W2.3 state) no longer trips on every commit,
        # only on diffs that ADD a marker to a file that already has
        # the other side.
        if [ "$has_xpage" -eq 0 ] || [ "$has_pageheader" -eq 0 ]; then
            continue
        fi
        # ALSO check the BASE file (HEAD for working/staged, or
        # $BASE_REF for diff mode). If the BASE already has BOTH
        # markers, then the file is in a known-bad pre-migration
        # state, and the current diff is just perpetuating it. We
        # do NOT want to fire on a diff that merely re-emits a
        # marker line that was already there (e.g. when git treats a
        # line as + because the surrounding newline state changed).
        # The "diff is responsible for the combo" criterion is: the
        # base does NOT have both markers. If it does, the file was
        # already bad before this commit; the tripwire ignores the
        # diff.
        local base_ref=""
        case "$MODE" in
            working|diff) base_ref="HEAD" ;;
            staged)        base_ref="HEAD" ;;   # staged diff is vs HEAD
        esac
        local base_has_xpage=0 base_has_pageheader=0
        if git show "$base_ref:$f" 2>/dev/null | grep -qE '<x-mm\.page[> \t/]'; then
            base_has_xpage=1
        fi
        if git show "$base_ref:$f" 2>/dev/null | grep -qE "@section\(\s*['\"]page-header['\"]"; then
            base_has_pageheader=1
        fi
        if [ "$base_has_xpage" -eq 1 ] && [ "$base_has_pageheader" -eq 1 ]; then
            continue
        fi
        local diff_args=""
        case "$MODE" in
            working) diff_args="-U0 --" ;;
            staged)  diff_args="-U0 --cached --" ;;
            diff)    diff_args="-U0 $BASE_REF...HEAD --" ;;
        esac
        local hits
        hits="$(git diff $diff_args "$f" 2>/dev/null \
            | awk -v file="$f" '
                BEGIN {
                    new_line = 0
                    old_line = 0
                    in_hunk  = 0
                }
                /^@@/ {
                    rest = $0
                    sub(/^@@ /, "", rest)
                    sub(/ @@.*$/, "", rest)
                    split(rest, parts, " ")
                    split(parts[1], a, ",")
                    split(parts[2], b, ",")
                    old_line = a[1] + 0
                    new_line = b[1] + 0
                    in_hunk  = 1
                    next
                }
                # File headers live OUTSIDE a hunk - see the comment
                # in tripwire_money_line for the in_hunk gate rationale.
                /^---/        { if (!in_hunk) next }
                /^\+\+\+/    { if (!in_hunk) next }
                /^\+/ {
                    if (!in_hunk) next
                    line = substr($0, 2)
                    low  = tolower(line)
                    # The diff is responsible for the W2.3 combo when
                    # it ADDS one of the two markers. The pre-check at
                    # the top of tripwire_xpage_pageheader_combo
                    # already established that the file (working tree)
                    # has BOTH markers, so any new addition of either
                    # one perpetuates the bad state.
                    added_marker = ""
                    if (low ~ /<x-mm\.page[> \t\/]/) added_marker = "<x-mm.page>"
                    else if (line ~ /@section\([^)]*page-header[^)]*\)/) added_marker = "@section('page-header')"
                    if (added_marker != "") {
                        printf("    %s:+%d  (new %s)\n", file, new_line, added_marker)
                    }
                    new_line++
                }
                /^-/ {
                    if (!in_hunk) next
                    old_line++
                }
                /^ / {
                    if (!in_hunk) next
                    new_line++
                    old_line++
                }
            ')"
        if [ -n "$hits" ]; then
            report "page-header + x-page combo in $f" \
                "  Diff ADDS a marker that perpetuates the W2.3 combo" \
                '  (file already has <x-mm.page> AND @section('page-header')):' \
                "$hits" \
                '  Remove the @section('page-header') ... @stop block - the master' \
                "  layout does not @yield it. See docs/FRONTEND-DEV-PLAN.md W2.3."
            found=1
        fi
    done < <(collect_blade_files)
    return $found
}



# -----------------------------------------------------------------------------
# Tripwire 4: new <style> tag in a non-CSS Blade file.
#
# Per docs/FRONTEND-DEV-PLAN.md §0: "New CSS only in `tokens.css` /
# component partials; per-page `<style>` shrinks, never grows." A diff
# that ADDS a new <style> opening tag to a Blade view or page (i.e.
# any Blade file under resources/views/ or module/*/views/ whose path
# does NOT end in _css/*.blade.php) is a violation — the new CSS
# belongs in a tokens.css / component partial instead.
#
# This is a DIFF check (not a file-content check), so pre-existing
# <style> blocks in legacy files do not trip the guard. The migration
# in W2.3 / W3 / W3.1-twin was deliberately allowed to remove those
# blocks one at a time; this tripwire only fires on NEW additions.
#
# The check is intentionally narrow: it fires on the opening <style>
# tag (matched as `<style` followed by a tag terminator: space, tab,
# `>`, or `/` — so `<link rel="stylesheet">` is NOT matched because
# the char after `<style` is `s`, not a terminator). Closing tags
# (`</style>`) and inline `style="…"` attributes are also not matched.
# -----------------------------------------------------------------------------
tripwire_raw_style_tag() {
    local f
    local found=0
    while IFS= read -r f; do
        [ -z "$f" ] && continue
        [ ! -f "$f" ] && continue
        # Skip the CSS partial directories — those are exactly where
        # new <style> blocks ARE supposed to live. The plan's
        # component-partial pattern is `module/.../views/.../_css/*.blade.php`
        # and `resources/views/.../_css/*.blade.php`; the `tokens.css`
        # file lives outside the Blade tree.
        case "$f" in
            *_css/*.blade.php) continue ;;
        esac
        local diff_args=""
        case "$MODE" in
            working) diff_args="-U0 --" ;;
            staged)  diff_args="-U0 --cached --" ;;
            diff)    diff_args="-U0 $BASE_REF...HEAD --" ;;
        esac
        local hits
        hits="$(git diff $diff_args "$f" 2>/dev/null \
            | awk -v file="$f" '
                BEGIN {
                    new_line = 0
                    old_line = 0
                    in_hunk  = 0
                }
                /^@@/ {
                    rest = $0
                    sub(/^@@ /, "", rest)
                    sub(/ @@.*$/, "", rest)
                    split(rest, parts, " ")
                    split(parts[1], a, ",")
                    split(parts[2], b, ",")
                    old_line = a[1] + 0
                    new_line = b[1] + 0
                    in_hunk  = 1
                    next
                }
                # Skip the "--- a/file" and "+++ b/file" file headers.
                # They are NEVER inside a hunk and never contain a <style>
                # tag, so an in_hunk gate is sufficient and correct.
                /^---/        { if (!in_hunk) next }
                /^\+\+\+/    { if (!in_hunk) next }
                /^\+/ {
                    if (!in_hunk) next
                    line = substr($0, 2)
                    low  = tolower(line)
                    # Match the opening <style> tag. The char after
                    # "<style" must be a tag terminator (space, tab,
                    # ">", or "/") so we do NOT match
                    #   <link rel="stylesheet" href="…">
                    # (the char after "<style" is "s", the start of
                    # "stylesheet"). Case-insensitive: HTML5 allows
                    # <STYLE>, <Style>, etc.
                    if (low ~ /<style[> \t\/]/) {
                        printf("    %s:+%d\n", file, new_line)
                    }
                    new_line++
                }
                # We do NOT need a `-` branch: removed <style> blocks
                # are the GOOD direction (the plan says "shrinks, never
                # grows"). We do need to keep old_line in sync for any
                # `-` content line in case a future tripwire wants to
                # also check removed lines, but the current spec only
                # flags new additions.
                /^-/ {
                    if (!in_hunk) next
                    old_line++
                }
                /^ / {
                    if (!in_hunk) next
                    new_line++
                    old_line++
                }
            ')"
        if [ -n "$hits" ]; then
            report "new <style> tag in $f" \
                "  New <style> opening tag in a non-CSS Blade file:" \
                "$hits" \
                "  Move the CSS into a tokens.css entry or a _css/*.blade.php partial." \
                "  See docs/FRONTEND-DEV-PLAN.md §0 (guardrails) for the rule."
            found=1
        fi
    done < <(collect_blade_files)
    return $found
}

# -----------------------------------------------------------------------------
# Tripwire 5: x-mm.field misuse.
#
# Two sub-checks per docs/FRONTEND-DEV-PLAN.md W4.3b:
#
# (1) On form pages (create / edit / form), a <x-mm.field> in a
#     + line that lives in a hunk containing input-group or
#     chosen-select. The x-mm.field component cannot render the
#     currency input-group addon; the two patterns must not mix
#     in the same hunk. (This applies on form pages only; x-mm.field
#     is not used outside forms.)
#
# (2) On form pages (create / edit / form), a <x-mm.field> with
#     name= but no id= on the same line. The component requires
#     an id prop (used by <label for="..."> and the input
#     id="..."); without it, the rendered HTML has empty for=""
#     and id="", breaking accessibility and label-clicking.
#     Non-form uses (e.g. a search/filter input on an index page)
#     are out of scope.
#
# Both sub-checks are diff-based (scanning + and - lines in the
# working-tree diff). The awk uses the same in_hunk flag pattern
# as the other diff-based tripwires; the file headers (--- a/file
# / +++ b/file) are skipped via the in_hunk gate, and the hunk
# header (^@@) resets the hunk_removes_group state.
# -----------------------------------------------------------------------------
tripwire_xmm_field_misuse() {
    local f
    local found=0
    while IFS= read -r f; do
        [ -z "$f" ] && continue
        [ ! -f "$f" ] && continue
        # File-path filter: x-mm.field is a form-field component, so
        # BOTH sub-checks are gated on form pages. Sub-check 1
        # (input-group/chosen-select coexistence) only matters on
        # form pages where the replacement would break the
        # currency input-group addon. Sub-check 2 (id= prop
        # required) also matters only where the rendered field is
        # paired with a <label for=...>; that pairing is the form
        # pattern. Other uses of x-mm.field (e.g. a search/filter
        # input on an index page) are out of scope — the
        # developer can add id= when they add a <label>. We run
        # both sub-checks only on form pages.
        local is_form_page=0
        case "$f" in
            *create.blade.php|*edit.blade.php|*form.blade.php) is_form_page=1 ;;
        esac
        [ "$is_form_page" -eq 0 ] && continue
        local diff_args=""
        case "$MODE" in
            working) diff_args="-U0 --" ;;
            staged)  diff_args="-U0 --cached --" ;;
            diff)    diff_args="-U0 $BASE_REF...HEAD --" ;;
        esac
        local hits
        hits="$(git diff $diff_args "$f" 2>/dev/null \
            | awk -v file="$f" -v form_page="$is_form_page" '
                BEGIN {
                    new_line            = 0
                    old_line            = 0
                    in_hunk             = 0
                    # Track whether the current hunk REMOVES an
                    # input-group or chosen-select (i.e. a - line
                    # has the marker). The replacement pattern is
                    # what W4.3b flags: a + line adds <x-mm.field>
                    # AND a - line in the same hunk removes an
                    # input-group or chosen-select. Tracking + lines
                    # as well would fire false positives when a
                    # developer adds an input-group to a form that
                    # already has <x-mm.field> (the x-mm.field is
                    # just re-emitted by git as a + line because of
                    # newline state changes, not because the
                    # developer is adding it).
                    hunk_removes_group = 0
                }
                /^@@/ {
                    rest = $0
                    sub(/^@@ /, "", rest)
                    sub(/ @@.*$/, "", rest)
                    split(rest, parts, " ")
                    split(parts[1], a, ",")
                    split(parts[2], b, ",")
                    old_line           = a[1] + 0
                    new_line           = b[1] + 0
                    in_hunk            = 1
                    hunk_removes_group = 0
                    next
                }
                # File headers live OUTSIDE a hunk.
                /^---/        { if (!in_hunk) next }
                /^\+\+\+/    { if (!in_hunk) next }
                /^\+/ {
                    if (!in_hunk) next
                    line = substr($0, 2)
                    # Sub-check 1 (form pages only): a new <x-mm.field>
                    # in a hunk that REMOVES an input-group or
                    # chosen-select. The W4.3b rule says x-mm.field
                    # cannot render the currency input-group addon;
                    # the replacement pattern (input-group -,
                    # x-mm.field +) is the actual violation. The
                    # hunk-level state is set by - lines (see below),
                    # so by the time we reach a + line, the state
                    # already reflects any removed input-group /
                    # chosen-select in the same hunk.
                    if (form_page == 1 && line ~ /<x-mm\.field/ && hunk_removes_group) {
                        printf("    %s:+%d  (x-mm.field added in hunk that removes input-group / chosen-select)\n", file, new_line)
                    }
                    # Sub-check 2 (form pages only): <x-mm.field>
                    # with name= but no id= on the same line. The
                    # x-mm.field component requires an id prop
                    # (used by <label for=...> and the input
                    # id=...); without it, the rendered HTML has
                    # empty for="" and id="" which breaks
                    # accessibility and label-clicking. Other
                    # uses of x-mm.field (e.g. a search/filter
                    # input on an index page) are out of scope —
                    # the developer can add id= when they add a
                    # <label>. Previously this check ran on all
                    # files, producing noise on every non-form
                    # use; the form-page gate keeps the W4.3b
                    # rule tight.
                    if (form_page == 1 && line ~ /<x-mm\.field/ && line ~ /name=/) {
                        if (line !~ /id=/) {
                            printf("    %s:+%d  (x-mm.field has name= but no id=)\n", file, new_line)
                        }
                    }
                    new_line++
                }
                /^-/ {
                    if (!in_hunk) next
                    line = substr($0, 2)
                    if (line ~ /input-group/ || line ~ /chosen-select/) {
                        hunk_removes_group = 1
                    }
                    old_line++
                }
                /^ / {
                    if (!in_hunk) next
                    new_line++
                    old_line++
                }
            ')"
        if [ -n "$hits" ]; then
            report "x-mm.field misuse in $f" \
                "  x-mm.field components must follow the W4.3b rules:" \
                "$hits" \
                "  - On form pages, do not REPLACE a raw input-group / chosen-select with <x-mm.field>" \
                "    in the same hunk (x-mm.field cannot render the currency input-group addon; keep the raw input)." \
                "  - Every <x-mm.field> with name= must also have id= (used by <label for=...>)." \
                "  See docs/FRONTEND-DEV-PLAN.md W4.3b."
            found=1
        fi
    done < <(collect_blade_files)
    return $found
}


# -----------------------------------------------------------------------------
# Tripwire 6: number_format(...) addition in money context.
#
# Per docs/FRONTEND-DEV-PLAN.md §0 rule 2: "No money/amount/
# currency math changes in any UI diff." The original
# tripwire 1 (money-line) catches additions of amount-keyword
# lines (total, amount, due, etc.) in money context, but it
# misses the cases where the column or expression is RENAMED
# (e.g. $x->grandTotal in camelCase) or where the
# number_format() call itself is moved to a different
# expression that does not carry the keyword. This tripwire
# closes the gap: any + line that ADDS a number_format(...)
# call in money context is flagged for review, regardless of
# whether the line contains an amount keyword.
#
# False positives: a number_format(...) added in money context
# for a non-money quantity (e.g. room count, phone number) is
# still a money-context change and warrants review. Override
# with <!-- money-travel-on: not money, room count --> on the
# affected line, or use a block-level override for whole-partial
# files.
#
# Design: diff-based (scans + lines in the working-tree diff).
# The same in_hunk flag pattern as the other diff-based
# tripwires protects against the file-header false positive
# on --- a/file / +++ b/file lines. The per-line and
# block-level override machinery is shared with tripwire 1
# (same money-travel-on / money-travel-on-block markers), so
# a developer who already trippped tripwire 1 can reuse the
# same override comment for tripwire 6.
# -----------------------------------------------------------------------------
tripwire_money_math() {
    local f
    local found=0
    while IFS= read -r f; do
        [ -z "$f" ] && continue
        [ ! -f "$f" ] && continue
        # File-context check: same as tripwire_money_line. Either
        # the file path carries a money-context word, OR a + line in
        # the diff will (the awk has_ctx check covers the second
        # case at the line level).
        local context=""
        case "$f" in
            *invoice*|*checkout*|*payment*|*voucher*|*receipt*) context=1 ;;
        esac
        local diff_args=""
        case "$MODE" in
            working) diff_args="-U0 --" ;;
            staged)  diff_args="-U0 --cached --" ;;
            diff)    diff_args="-U0 $BASE_REF...HEAD --" ;;
        esac
        local override_lines_added override_lines_removed
        override_lines_added="$(grep -nE '(<!--|\{\{--)\s*money-travel-on:' "$f" 2>/dev/null | cut -d: -f1 | paste -sd, -)"
        override_lines_removed="$(git show "HEAD:$f" 2>/dev/null | grep -nE '(<!--|\{\{--)\s*money-travel-on:' | cut -d: -f1 | paste -sd, -)"
        local block_lo block_hi
        local file_line_count
        file_line_count="$(wc -l < "$f" 2>/dev/null | tr -d ' ')"
        block_lo="$(grep -nE '\{\{--\s*money-travel-on-block:' "$f" 2>/dev/null | head -1 | cut -d: -f1)"
        if [ -n "$block_lo" ]; then
            local end_line
            end_line="$(awk -v start="$block_lo" '
                NR > start && /\{\{--\s*money-travel-on-end\s*--\}\}/ { print NR; exit }
            ' "$f" 2>/dev/null)"
            if [ -n "$end_line" ]; then
                block_hi="$end_line"
            else
                block_hi="$file_line_count"
            fi
        else
            block_lo=""
            block_hi=""
        fi
        local hits
        hits="$(git diff $diff_args "$f" 2>/dev/null \
            | awk -v file="$f" -v ctx="$context" -v add_ov="$override_lines_added" -v rem_ov="$override_lines_removed" -v blk_lo="$block_lo" -v blk_hi="$block_hi" '
                BEGIN {
                    n_ov_add = split(add_ov, add_arr, ",")
                    n_ov_rem = split(rem_ov, rem_arr, ",")
                    blk_lo = blk_lo + 0
                    blk_hi = blk_hi + 0
                    in_block = 0
                    new_line = 0
                    old_line = 0
                    in_hunk  = 0
                }
                function in_block_range(fl) {
                    # A money-travel-on-block marker on any line in
                    # the file opts the WHOLE file out of the
                    # tripwire. This is the right tool for
                    # whole-partial files that contain a lot of
                    # money math (booking rows, invoice tables,
                    # payment receipts) where per-line overrides
                    # would be noise. The awk only needs a yes/no;
                    # the caller computes the marker line before
                    # invoking us.
                    if (blk_lo <= 0) return 0
                    return 1
                }
                /^@@/ {
                    rest = $0
                    sub(/^@@ /, "", rest)
                    sub(/ @@.*$/, "", rest)
                    split(rest, parts, " ")
                    split(parts[1], a, ",")
                    split(parts[2], b, ",")
                    old_line = a[1] + 0
                    new_line = b[1] + 0
                    in_hunk  = 1
                    next
                }
                # File headers live OUTSIDE a hunk.
                /^---/        { if (!in_hunk) next }
                /^\+\+\+/    { if (!in_hunk) next }
                /^\+/ {
                    if (!in_hunk) next
                    line = substr($0, 2)
                    low  = tolower(line)
                    # The tripwire fires on a + line that adds a
                    # number_format(...) call in money-context.
                    # The keyword regex is intentionally permissive
                    # — any number_format call counts, regardless
                    # of what the formatted expression is named.
                    # The W6 guardrail says NO money-math changes
                    # in UI diffs; a number_format(...) ADDED in
                    # an invoice / payment / voucher / receipt /
                    # booking context is exactly the kind of change
                    # that needs explicit review (it could be a
                    # rename of an existing total, a new precision
                    # spec, a moved expression, a new column that
                    # happens to be money, etc.).
                    #
                    # The keyword regex is case-insensitive: number_format
                    # is a PHP built-in (always lowercase), but
                    # Number_format and NUMBER_FORMAT are both valid
                    # PHP and we want to catch them.
                    has_format = (low ~ /number_format\(/)
                    has_ctx    = (ctx != "") || (low ~ /invoice|checkout|payment|voucher|receipt|booking/)
                    if (has_format && has_ctx) {
                        fl = new_line
                        overridden = 0
                        for (i = 1; i <= n_ov_add; i++) {
                            if (add_arr[i]+0 == fl) { overridden = 1; break }
                        }
                        if (!overridden && in_block_range(fl)) overridden = 1
                        if (!overridden) {
                            printf("    %s:+%d\n", file, fl)
                        }
                    }
                    new_line++
                }
                # The - branch only needs to keep old_line in sync;
                # the tripwire only flags additions (a REMOVED
                # number_format(...) is the GOOD direction — the
                # rule says "no money-math changes", and removing
                # the math is not a change to it).
                /^-/ {
                    if (!in_hunk) next
                    old_line++
                }
                /^ / {
                    if (!in_hunk) next
                    new_line++
                    old_line++
                }
            ')"
        if [ -n "$hits" ]; then
            report "money-math change in $f" \
                "  Lines flagged (added a number_format(...) call in money context):" \
                "$hits" \
                "  Override with: <!-- money-travel-on: <reason> --> on the affected line." \
                "  See docs/FRONTEND-DEV-PLAN.md §0 (guardrails) for the rule."
            found=1
        fi
    done < <(collect_blade_files)
    return $found
}


# -----------------------------------------------------------------------------
# Tripwire 2: Blade directives (@include / @yield / @if / @foreach / @for / …)
# inside <style>…</style>.
#
# A Blade template that places a directive (@include, @yield, @stack, @push,
# @section, @once, or control-flow / loop / php directives like @if, @foreach,
# @for, @switch, @php) inside a <style>…</style> block is a round-10-class
# defect: an included partial or conditional block can emit its own <style>
# tags or non-CSS markup, breaking the outer block. Standard CSS at-rules
# (@media, @keyframes, @import, @font-face, @page, @supports, …) are allowed.
# -----------------------------------------------------------------------------
tripwire_nested_style_include() {
    local f
    local found=0
    while IFS= read -r f; do
        [ -z "$f" ] && continue
        [ ! -f "$f" ] && continue
        local diff_args=""
        # Use full-file context (-U999999) so that a <style> tag opened on an
        # unchanged context line (e.g. inside a *_css/*.blade.php partial where
        # tripwire 4 is exempt) still seeds in_style=1 before a + line inside
        # that block is reached. Untouched files still produce an empty diff.
        case "$MODE" in
            working) diff_args="-U999999 --" ;;
            staged)  diff_args="-U999999 --cached --" ;;
            diff)    diff_args="-U999999 $BASE_REF...HEAD --" ;;
        esac
        # The tripwire is diff-based: only flag a + line that ADDS a
        # @directive (include/yield/stack/push/section/once or control-flow
        # @if/@foreach/@for/...) inside a <style>...</style> block. Context
        # ( ) lines advance the state machine but do not trip; - lines are
        # ignored (the round-10 defect class is about ADDING a directive
        # inside a style block — a REMOVED directive is the GOOD direction).
        local hits
        hits="$(git diff $diff_args "$f" 2>/dev/null \
            | awk -v file="$f" '
                BEGIN {
                    in_blade_comment = 0
                    in_html_comment  = 0
                    in_script        = 0
                    in_style         = 0
                    in_hunk          = 0
                    new_line         = 0
                    directive_re     = "@(include|includeIf|includeWhen|includeUnless|includeFirst|yield|stack|push|section|once|if|elseif|else|endif|unless|endunless|foreach|endforeach|forelse|endforelse|for|endfor|while|endwhile|switch|endswitch|php|endphp)([^a-zA-Z0-9_-]|$)"
                }
                # File headers are always outside a hunk.
                /^---/        { if (!in_hunk) next }
                /^\+\+\+/    { if (!in_hunk) next }
                /^@@/ {
                    rest = $0
                    sub(/^@@ /, "", rest)
                    sub(/ @@.*$/, "", rest)
                    split(rest, parts, " ")
                    split(parts[2], b, ",")
                    new_line = b[1] + 0
                    in_hunk  = 1
                    next
                }
                # The - branch is ignored. A REMOVED @directive
                # inside a style block is the GOOD direction; a
                # removal cannot re-introduce the round-10 defect.
                /^-/ {
                    if (!in_hunk) next
                    next
                }
                # + and space lines: do the strip + scan, and trip
                # only on the + case.
                /^[+ ]/ {
                    if (!in_hunk) next
                    is_plus = (substr($0, 1, 1) == "+")
                    line = substr($0, 2)
                    # ---- Phase A: strip comments and scripts ----
                    cleaned = ""
                    pos = 1
                    while (pos <= length(line)) {
                        next_at = 0; next_kind = ""
                        if (in_blade_comment) {
                            i = index(substr(line, pos), "--}}")
                            if (i > 0 && (next_at == 0 || i < next_at)) {
                                next_at = i; next_kind = "blade_close"
                            }
                        }
                        if (!in_blade_comment) {
                            i = index(substr(line, pos), "{{--")
                            if (i > 0 && (next_at == 0 || i < next_at)) {
                                next_at = i; next_kind = "blade_open"
                            }
                        }
                        if (in_html_comment) {
                            i = index(substr(line, pos), "-->")
                            if (i > 0 && (next_at == 0 || i < next_at)) {
                                next_at = i; next_kind = "html_close"
                            }
                        }
                        if (!in_html_comment) {
                            i = index(substr(line, pos), "<!--")
                            if (i > 0 && (next_at == 0 || i < next_at)) {
                                next_at = i; next_kind = "html_open"
                            }
                        }
                        if (in_script) {
                            i = index(substr(line, pos), "</script>")
                            if (i > 0 && (next_at == 0 || i < next_at)) {
                                next_at = i; next_kind = "script_close"
                            }
                        }
                        if (!in_script) {
                            rest = substr(line, pos)
                            idx = index(rest, "<script")
                            while (idx > 0) {
                                tail_pos = idx + length("<script")
                                if (tail_pos > length(rest)) break
                                ch = substr(rest, tail_pos, 1)
                                if (ch == " " || ch == ">" || ch == "\t" || ch == "\n") {
                                    i = idx
                                    if (next_at == 0 || i < next_at) {
                                        next_at = i; next_kind = "script_open"
                                    }
                                    break
                                }
                                idx = index(substr(rest, tail_pos), "<script")
                                if (idx > 0) idx = idx + tail_pos - 1
                            }
                        }
                        if (next_at == 0) {
                            if (!in_blade_comment && !in_html_comment && !in_script) {
                                cleaned = cleaned substr(line, pos)
                            }
                            break
                        }
                        if (!in_blade_comment && !in_html_comment && !in_script) {
                            seg = substr(line, pos, next_at - 1)
                            cleaned = cleaned seg
                        }
                        if (next_kind == "blade_close")   { pos = pos + next_at - 1 + length("--}}");      in_blade_comment = 0 }
                        else if (next_kind == "blade_open")   { pos = pos + next_at - 1 + length("{{--");       in_blade_comment = 1 }
                        else if (next_kind == "html_close")   { pos = pos + next_at - 1 + length("-->");        in_html_comment  = 0 }
                        else if (next_kind == "html_open")    { pos = pos + next_at - 1 + length("<!--");       in_html_comment  = 1 }
                        else if (next_kind == "script_close") { pos = pos + next_at - 1 + length("</script>"); in_script        = 0 }
                        else if (next_kind == "script_open")  { pos = pos + next_at - 1 + length("<script");   in_script        = 1 }
                    }
                    # ---- Phase B: walk style blocks on cleaned ----
                    pos = 1
                    while (pos <= length(cleaned)) {
                        next_at = 0; next_kind = ""
                        if (in_style) {
                            i = index(substr(cleaned, pos), "</style>")
                            if (i > 0 && (next_at == 0 || i < next_at)) {
                                next_at = i; next_kind = "style_close"
                            }
                        }
                        if (!in_style) {
                            i = index(substr(cleaned, pos), "<style")
                            if (i > 0 && (next_at == 0 || i < next_at)) {
                                tail_pos = pos + i - 1 + length("<style")
                                if (tail_pos <= length(cleaned)) {
                                    ch = substr(cleaned, tail_pos, 1)
                                    if (ch == " " || ch == ">" || ch == "\t") {
                                        next_at = i
                                        next_kind = "style_open"
                                    }
                                }
                            }
                        }
                        if (next_at == 0) {
                            if (in_style) {
                                tail = substr(cleaned, pos)
                                if (is_plus && tail ~ directive_re) {
                                    printf("    %s:+%d: %s\n", file, new_line, $0)
                                }
                            }
                            break
                        }
                        if (in_style) {
                            seg = substr(cleaned, pos, next_at - 1)
                            if (is_plus && seg ~ directive_re) {
                                printf("    %s:+%d: %s\n", file, new_line, $0)
                            }
                        }
                        if (next_kind == "style_close") {
                            pos = pos + next_at - 1 + length("</style>")
                            in_style = 0
                        } else if (next_kind == "style_open") {
                            open_pos = pos + next_at - 1
                            close_pos = index(substr(cleaned, open_pos), ">")
                            if (close_pos == 0) {
                                pos = length(cleaned) + 1
                                in_style = 1
                            } else {
                                pos = open_pos + close_pos
                                in_style = 1
                            }
                        }
                    }
                    new_line++
                }
            ')"
        if [ -n "$hits" ]; then
            report "nested Blade directive inside <style> in $f" \
                "  Blade directive (@include/@yield/@stack/@if/@foreach/@for/…) ADDED inside a <style> block:" \
                "$hits" \
                "  Move the directive outside the <style> tag, or use CSS classes/variables instead." \
                "  This is the round-10 defect class - see docs/BUGS.md."
            found=1
        fi
    done < <(collect_blade_files)
    return $found
}
echo "ui-guard: mode=$MODE, base=$BASE_REF"
echo "ui-guard: scanning $(collect_blade_files | wc -l) Blade files"

tripwire_money_line || true
tripwire_nested_style_include || true
tripwire_xpage_pageheader_combo || true
tripwire_raw_style_tag || true
tripwire_xmm_field_misuse || true
tripwire_money_math || true

if [ "$fail" -ne 0 ]; then
    echo
    echo "ui-guard: at least one tripwire triggered." >&2
    exit 1
fi

echo "ui-guard: no tripwires triggered."
exit 0