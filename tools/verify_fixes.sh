#!/usr/bin/env bash
# =============================================================================
# verify_fixes.sh — check the routes fixed in PR #4 (bug round 1)
#
# Usage:
#   BASE=https://staging.example.com EMAIL=admin@x.com PASS=secret ./tools/verify_fixes.sh
#
#   BASE  (required)  scheme+host of the instance to test (point it at STAGING,
#                     not production — this logs in and hits real routes).
#   Set ALLOW_LIVE=1 to skip the production-refusal guard.
#
# Checks each previously-broken URL after login and expects the NEW behavior
# (200 = page works, 302 = intentional redirect, 404 = intentionally removed
# route). Anything else (500, timeout) is reported FAIL.
# =============================================================================
set -u
BASE="${BASE:-}"
EMAIL="${EMAIL:-}"
PASS="${PASS:-}"
ALLOW_LIVE="${ALLOW_LIVE:-0}"

[ -z "$BASE" ] && { echo "Set BASE (e.g. BASE=https://staging.dizihotel.com)"; exit 2; }
case "$BASE" in
  *mm-heritage-hotel.dizihotel.com*)
    [ "$ALLOW_LIVE" = "1" ] || { echo "Refusing to run against the LIVE production site (it is unpatched —"; echo "several old bugs are cache-affecting). Point BASE at a staging deploy of this branch."; exit 2; } ;;
esac
JAR="$(mktemp)"; trap 'rm -f "$JAR"' EXIT
CURL="curl -sk --max-time 25 -b $JAR -c $JAR -o /dev/null -w %{http_code}"

# ---------------------------------------------------------------- login -----
echo "== login $BASE"
LOGIN_HTML=$(curl -sk --max-time 25 -c "$JAR" "$BASE/login")
TOKEN=$(echo "$LOGIN_HTML" | grep -oE 'name="_token"[^>]*value="[^"]+"' | head -1 | grep -oE 'value="[^"]+"' | sed 's/value="//;s/"//')
[ -z "$TOKEN" ] && { echo "FAIL: could not read CSRF token from $BASE/login (is the app up?)"; exit 2; }
CODE=$(curl -sk -c "$JAR" -o /dev/null -w '%{http_code}' -X POST "$BASE/login" \
  --data-urlencode "_token=$TOKEN" --data-urlencode "email=$EMAIL" --data-urlencode "password=$PASS")
case "$CODE" in
  302|200) echo "   login POST -> $CODE (ok)";;
  *) echo "   login POST -> $CODE (credentials wrong? CAPTCHA?)"; echo "   Continuing unauthenticated checks only.";;
esac

pass=0; fail=0
chk() { # chk <expect> <path> [label]
  local expect="$1" path="$2" label="${3:-$2}" code
  code=$($CURL "$BASE$path" 2>/dev/null || echo 000)
  case " ${expect} " in
    *" $code "*) pass=$((pass+1)); printf '  PASS  %-4s %s\n' "$code" "$label";;
    *)          fail=$((fail+1)); printf '  FAIL  got %s want[%s] %s\n' "$code" "$expect" "$label";;
  esac
}

echo
echo "== previously-500 admin screens -> now 200 (or documented 302/404)"
chk "200"            "/hotel/booking/create"                          "booking create (CRM class fix)"
chk "200 302"        "/hotel/booking/next-step"                       "booking next-step (302 ok without cart)"
chk "200"            "/hotel/guests/create"                           "guest create (Guest->CRM fix)"
chk "200"            "/hotel/guest-registration-terms/create"         "guest registration terms create"
chk "200"            "/hotel/booking-note/create"                     "booking note create"
chk "200"            "/hotel/booking/booking-adjusts"                 "booking adjusts index"
chk "200"            "/hotel/booking-search-by-date?booking_search_date=$(date +%F)" "booking search-by-date (\$account_types fix)"
chk "200"            "/setting/parent-permissions/create"             "parent-permission create() added"
chk "200"            "/setting/submodules/create"                     "submodule create() added"
chk "200"            "/setting/view-permitted-users"                  "permitted users page"
chk "200"            "/setting/select/employee/list?q="               "employee select2 JSON (HRM-guarded)"
chk "200"            "/setting/permitted/employee/list"               "permitted employee JSON (HRM-guarded)"
chk "200"            "/setup/account-setups"                          "new account-setups index view"
chk "200"            "/reports/revenue-analysis"                      "new report: revenue analysis"
chk "200"            "/reports/ratio-analysis"                        "new report: ratio analysis"
chk "200"            "/reports/nominal-account-ledger"                "new report: nominal account ledger"
chk "200"            "/reports/received-payment-statement"            "new report: received payments"
chk "200"            "/gs/item-export"                                "item CSV export (class restored)"
chk "200"            "/sale/acc-returnable-sale-items"               "returnable sale items -> empty, no crash"
chk "404"            "/sync-data"                                     "sync-data now clean 404 (no HRM), not 500"

echo
echo "== routes intentionally narrowed -> must be 404, not 500"
chk "404"            "/hotel/room-management/vat/create"              "vat create route removed"
chk "404"            "/hotel/room-management/account-type/create"     "account-type create removed"
chk "404"            "/setup/account-opening-balances"                "opening-balances index removed"
chk "200"            "/setup/account-opening-balances/create"         "opening-balances create still works"
chk "404"            "/hotel_service/services/create"                 "hotel-service create removed"

echo
echo "== deliberate redirects (302) with flash"
chk "302"            "/setting/permission-access"                     "redirects to permitted users"
chk "302"            "/sale/acc_collections/create"                   "collection stub -> redirect to index"
chk "302"            "/purchase/acc-payments/create"                  "payment stub -> redirect to index"

echo
echo "== security: unauthenticated access must NOT succeed (302 to login)"
U="$(mktemp)"; CU="curl -sk --max-time 15 -o /dev/null -w %{http_code} -c $U"
for r in /optimize-clear /update-debug /db-backup; do
  code=$($CU "$BASE$r")
  case "$code" in
    302|401|403|404) pass=$((pass+1)); printf '  PASS  %-4s unauth %s\n' "$code" "$r";;
    *)               fail=$((fail+1)); printf '  FAIL  got %s unauth %s  <<< production deploy still vulnerable if this is prod!\n' "$code" "$r";;
  esac
done
rm -f "$U"

echo
echo "== public frontend: bad ids/slugs must be 404, not 500"
chk "404"            "/room/definitely-not-a-slug-xyz"                "unknown room slug"
chk "200"            "/"                                              "home page still 200"

echo
echo "============================================================"
echo " PASS: $pass   FAIL: $fail"
[ "$fail" -eq 0 ] && echo " ALL CHECKS OK" || echo " Some checks failed — see FAIL lines above and storage/logs/laravel.log"
exit $(( fail > 0 ? 1 : 0 ))
