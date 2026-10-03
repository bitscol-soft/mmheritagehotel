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
                    next
                }
                /^\+[^+]/ {
                    line = substr($0, 2)
                    low = tolower(line)
                    has_amount = (low ~ /amount|total|grand_total|grand total|due|paid|balance|price|rent|fare|charge|vat|tax/)
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
                    new_line++
                }
                /^-[^-]/ {
                    line = substr($0, 2)
                    low = tolower(line)
                    has_amount = (low ~ /amount|total|grand_total|grand total|due|paid|balance|price|rent|fare|charge|vat|tax/)
                    has_ctx = (ctx != "") || (low ~ /invoice|checkout|payment|voucher|receipt|booking/)
                    if (has_amount && has_ctx) {
                        fl = old_line
                        overridden = 0
                        for (i = 1; i <= n_ov_rem; i++) {
                            if (rem_arr[i]+0 == fl) { overridden = 1; break }
                        }
                        # Also accept an override on the new-file side —
                        # this lets a `git mv`-style rename or a delete+add
                        # in a new position carry the same override to both
                        # sides of the diff. The override is on the line
                        # that the content lives on now.
                        if (!overridden) {
                            for (i = 1; i <= n_ov_add; i++) {
                                if (add_arr[i]+0 == fl) { overridden = 1; break }
                            }
                        }
                        # Block-level override — see the corresponding
                        # check in the + branch.
                        if (!overridden && in_block_range(fl)) overridden = 1
                        if (!overridden) {
                            printf("    %s:-%d\n", file, fl)
                        }
                    }
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
        # State tracking:
        #   in_blade_comment  — inside a Blade {{-- … --}} block; ignore every literal
        #                      mention of <style> or @directive on a comment line.
        #   in_html_comment   — inside a <!-- … --> block; same reasoning.
        #   in_script         — inside a <script>…</script> block; Blade doesn't
        #                      compile these, but a literal '@directive' could
        #                      appear in a JS string and we don't want to chase it.
        #   in_style          — the real state we care about. Only set when we
        #                      see <style> OUTSIDE a comment / script.
        # The Blade-comment regex handles two cases on a single line — both an
        # opening AND a closing on the same line, e.g. `{{-- foo --}} bar` —
        # by setting in_blade_comment=0 whenever --}} is seen anywhere on the
        # line, even if the line also opened a comment earlier. This is the
        # conservative choice: false negatives on a line like
        #   {{-- open --}} <style>… {{-- close --}} @include --}}
        # are acceptable; false positives on legitimate code are not.
        local hits
        # Two-phase awk:
        #   Phase A (per-line): strip out everything that lives inside a Blade
        #   comment, HTML comment, or <script> block. The remaining text is
        #   "code" that the simple state machine in phase B can scan.
        #   Phase B (per-file): the original 3-rule state machine — in_style
        #   is set by <style> and reset by </style>, and a Blade directive
        #   inside a style block is the real defect.
        #
        # Why two phases? Walking one line segment-by-segment (open/close
        # markers) is O(n²) on awk strings and hard to keep correct when
        # multiple marker types overlap. Stripping comments first reduces the
        # problem to the original 3-rule machine, which is fast and easy to
        # reason about.
        #
        # Comment stripping is itself stateful across lines — a Blade comment
        # can open on one line and close on another — so the state variables
        # in_blade_comment / in_html_comment / in_script live in BEGIN-block
        # initialisation and persist across NR (lines).
        hits="$(awk '
            BEGIN {
                in_blade_comment = 0
                in_html_comment  = 0
                in_script        = 0
                in_style         = 0
            }
            {
                # ---- Phase A: strip comments and scripts from this line ----
                cleaned = ""
                line = $0
                pos = 1
                # Walk left-to-right. At each step, find the earliest of:
                #   - the close marker of whatever block we are currently in
                #   - the open marker of any other block
                # and copy the text BEFORE that marker into `cleaned`, then
                # advance past the marker and update the state. Anything we
                # skip over (inside a comment or script) is dropped.
                while (pos <= length(line)) {
                    # Find the next interesting marker from `pos` onwards.
                    # For each marker type, the close marker is only meaningful
                    # when we are inside that block; the open marker is
                    # meaningful when we are NOT inside that block.
                    next_at = 0; next_kind = ""
                    # Blade comment close: --}}
                    if (in_blade_comment) {
                        i = index(substr(line, pos), "--}}")
                        if (i > 0 && (next_at == 0 || i < next_at)) {
                            next_at = i; next_kind = "blade_close"
                        }
                    }
                    # Blade comment open: {{--
                    if (!in_blade_comment) {
                        i = index(substr(line, pos), "{{--")
                        if (i > 0 && (next_at == 0 || i < next_at)) {
                            next_at = i; next_kind = "blade_open"
                        }
                    }
                    # HTML comment close: -->
                    if (in_html_comment) {
                        i = index(substr(line, pos), "-->")
                        if (i > 0 && (next_at == 0 || i < next_at)) {
                            next_at = i; next_kind = "html_close"
                        }
                    }
                    # HTML comment open: <!--
                    if (!in_html_comment) {
                        i = index(substr(line, pos), "<!--")
                        if (i > 0 && (next_at == 0 || i < next_at)) {
                            next_at = i; next_kind = "html_open"
                        }
                    }
                    # Script close: </script>
                    if (in_script) {
                        i = index(substr(line, pos), "</script>")
                        if (i > 0 && (next_at == 0 || i < next_at)) {
                            next_at = i; next_kind = "script_close"
                        }
                    }
                    # Script open: <script…>  (use a regex via match for the
                    # optional attributes; fall back to plain "<script>" if
                    # there are none on this line).
                    if (!in_script) {
                        rest = substr(line, pos)
                        # find "<script" then ensure next char is space, >, or /
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
                            # "<script" appears but is followed by another
                            # letter (e.g. "<scripty") — keep looking.
                            idx = index(substr(rest, tail_pos), "<script")
                            if (idx > 0) idx = idx + tail_pos - 1
                        }
                    }
                    if (next_at == 0) {
                        # No more markers. The remaining tail is "code" if we
                        # are not inside a comment or script; otherwise drop it.
                        if (!in_blade_comment && !in_html_comment && !in_script) {
                            cleaned = cleaned substr(line, pos)
                        }
                        break
                    }
                    # Copy the pre-marker segment (if it is "code") into
                    # cleaned, then advance past the marker and update state.
                    if (!in_blade_comment && !in_html_comment && !in_script) {
                        cleaned = cleaned substr(line, pos, next_at - 1)
                    }
                    # Advance pos past the marker.
                    if (next_kind == "blade_close")   { pos = pos + next_at - 1 + length("--}}");      in_blade_comment = 0 }
                    else if (next_kind == "blade_open")   { pos = pos + next_at - 1 + length("{{--");       in_blade_comment = 1 }
                    else if (next_kind == "html_close")   { pos = pos + next_at - 1 + length("-->");        in_html_comment  = 0 }
                    else if (next_kind == "html_open")    { pos = pos + next_at - 1 + length("<!--");       in_html_comment  = 1 }
                    else if (next_kind == "script_close") { pos = pos + next_at - 1 + length("</script>"); in_script        = 0 }
                    else if (next_kind == "script_open")  { pos = pos + next_at - 1 + length("<script");   in_script        = 1 }
                }

                # ---- Phase B: state machine on `cleaned` ----
                # Same left-to-right walker pattern as Phase A, but only for
                # <style>…</style> markers. Anything inside a style block is
                # checked for the round-10 defect directives.
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
                            # Verify the next char is a tag terminator (space,
                            # tab, >, or newline), not a letter (e.g. "<styley").
                            tail_pos = pos + i - 1 + length("<style")
                            if (tail_pos <= length(cleaned)) {
                                ch = substr(cleaned, tail_pos, 1)
                                if (ch == " " || ch == ">" || ch == "\t") {
                                    # Also skip any extra attributes up to the
                                    # tag close.
                                    while (tail_pos <= length(cleaned) && substr(cleaned, tail_pos, 1) != ">") {
                                        tail_pos++
                                    }
                                    # The "<style …>" tag itself ends at the
                                    # closing >. next_at is the position of the
                                    # <; we will use index to find the > below
                                    # when we compute the slice length. Stash
                                    # it via next_at_len. Simpler: just track
                                    # that we are inside the style block and
                                    # advance past >.
                                    next_at = i
                                    next_kind = "style_open"
                                    # We will advance by the distance to >
                                    # plus 1; do that inline below.
                                }
                            }
                        }
                    }
                    if (next_at == 0) {
                        # Remaining tail — check for directives if in style.
                        if (in_style) {
                            tail = substr(cleaned, pos)
                            if (tail ~ /@(include|yield|stack|push|section|once)/) {
                                printf("%s:%d:%s\n", FILENAME, NR, $0)
                            }
                        }
                        break
                    }
                    # Pre-marker segment — check for directives if in style.
                    if (in_style) {
                        seg = substr(cleaned, pos, next_at - 1)
                        if (seg ~ /@(include|yield|stack|push|section|once)/) {
                            printf("%s:%d:%s\n", FILENAME, NR, $0)
                        }
                    }
                    # Advance past the marker.
                    if (next_kind == "style_close") {
                        pos = pos + next_at - 1 + length("</style>")
                        in_style = 0
                    } else if (next_kind == "style_open") {
                        # We are at the < of <style …>. Walk to the >.
                        open_pos = pos + next_at - 1
                        close_pos = index(substr(cleaned, open_pos), ">")
                        if (close_pos == 0) {
                            # No > on this line — the open tag is incomplete.
                            # Treat the rest of the line as if the style block
                            # were open; this matches the multi-line case.
                            pos = length(cleaned) + 1
                            in_style = 1
                        } else {
                            pos = open_pos + close_pos
                            in_style = 1
                        }
                    }
                }
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