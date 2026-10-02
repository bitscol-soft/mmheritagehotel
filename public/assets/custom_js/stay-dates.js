/* Check-in / check-out fields on the booking create and edit forms (bootstrap-datepicker, yyyy-mm-dd).
 * - check-in cannot be before the business date (data-business-date), unless data-allow-past="1" (edit);
 * - check-out is always after check-in: invalid values are moved to check-in + 1 night, then the field's
 *   own `change` event fires so the existing night/amount code recalculates;
 * - changing check-in recalculates nights through the same event;
 * - submitBookingForm() is wrapped so an invalid stay is stopped with a message before the legacy checks run.
 * Messages are written with textContent only. */
(function (window, $) {
    'use strict';
    if (!$) return;
    var DATE = /^(\d{4})-(\d{2})-(\d{2})$/;

    function toUtc(value) {
        var m = DATE.exec(String(value || '').trim());
        if (!m) return null;
        var ms = Date.UTC(+m[1], +m[2] - 1, +m[3]);
        var d = new Date(ms);
        return d.getUTCFullYear() === +m[1] && d.getUTCMonth() === +m[2] - 1 && d.getUTCDate() === +m[3] ? ms : null;
    }
    function fromUtc(ms) {
        var d = new Date(ms), pad = function (n) { return (n < 10 ? '0' : '') + n; };
        return d.getUTCFullYear() + '-' + pad(d.getUTCMonth() + 1) + '-' + pad(d.getUTCDate());
    }
    function addDays(value, days) { var ms = toUtc(value); return ms === null ? null : fromUtc(ms + days * 86400000); }
    function nights(inValue, outValue) {
        var a = toUtc(inValue), b = toUtc(outValue);
        return a === null || b === null ? null : Math.round((b - a) / 86400000);
    }
    // Pure rule used by the fields and the submit guard.
    // Returns { checkIn, checkOut, messages: [] }; values are corrected, never left invalid.
    function resolve(checkIn, checkOut, min, allowPast) {
        var messages = [], inMs = toUtc(checkIn), outMs = toUtc(checkOut), minMs = toUtc(min);
        if (inMs === null) { messages.push('Enter the check-in date as YYYY-MM-DD.'); return { checkIn: checkIn, checkOut: checkOut, messages: messages, invalid: true }; }
        if (!allowPast && minMs !== null && inMs < minMs) {
            checkIn = min; inMs = minMs;
            messages.push('Check-in cannot be before the business date (' + min + ').');
        }
        if (outMs === null || outMs <= inMs) {
            if (outMs !== null || String(checkOut || '').trim() !== '') messages.push('Check-out must be after check-in; it was moved to the next night.');
            checkOut = fromUtc(inMs + 86400000);
        }
        return { checkIn: checkIn, checkOut: checkOut, messages: messages, invalid: false };
    }

    function init() {
        var $in = $('.check-in-date').first(), $out = $('.check-out-date-picker').first();
        if (!$in.length || !$out.length || $in.data('mmStay')) return;
        $in.data('mmStay', true);
        var min = $in.attr('data-business-date') || '', allowPast = $in.attr('data-allow-past') === '1';
        var lastOut = $out.val(), busy = false, appliedStart = {};

        function note($field) {
            var $group = $field.closest('.form-group'), el = $group.find('.mm-date-error')[0];
            if (!el) {
                el = document.createElement('span');
                el.className = 'mm-date-error text-danger';
                el.setAttribute('role', 'alert');
                el.hidden = true;
                $group[0].appendChild(el);
            }
            return el;
        }
        function say($field, messages) {
            var el = note($field);
            el.textContent = messages.join(' ');
            el.hidden = messages.length === 0;
            if (messages.length) $field.attr('aria-invalid', 'true'); else $field.removeAttr('aria-invalid');
        }
        // Re-read the input into the calendar without firing another change event.
        function refresh($field) { if ($field.data('datepicker')) $field.datepicker('update'); }
        function setStart($field, key, value) {
            if (!value || appliedStart[key] === value || !$field.data('datepicker')) return;
            appliedStart[key] = value;
            $field.datepicker('setStartDate', value);
        }
        function constrain() {
            if (!$.fn.datepicker) return;
            if (!allowPast && toUtc(min) !== null) setStart($in, 'in', min);
            if (!$out.hasClass('checkOut')) setStart($out, 'out', addDays($in.val(), 1));
        }
        function apply(changedField) {
            if (busy) return false;
            busy = true;
            try { return applyNow(changedField); } finally { busy = false; }
        }
        function applyNow(changedField) {
            var r = resolve($in.val(), $out.val(), min, allowPast);
            var outChanged = false;
            if (r.invalid) { say($in, r.messages); return false; }
            if ($in.val() !== r.checkIn) { $in.val(r.checkIn); refresh($in); }
            if ($out.val() !== r.checkOut) { $out.val(r.checkOut); refresh($out); outChanged = true; }
            constrain();
            say(changedField === 'in' ? $in : $out, r.messages);
            lastOut = $out.val();
            // Legacy listeners on the check-out field recalculate nights and amounts (and, on edit, room availability).
            if (outChanged || (changedField === 'in' && !$out.hasClass('checkOut'))) $out.trigger('change');
            return true;
        }
        $in.on('change changeDate', function () { apply('in'); });
        // Runs before the document-level legacy handler, so it sees the corrected value.
        $out.on('change', function () {
            if (busy) return;
            var r = resolve($in.val(), $out.val(), min, allowPast);
            if (r.invalid) return;
            if ($out.val() !== r.checkOut) { $out.val(r.checkOut); refresh($out); }
            say($out, r.messages.filter(function (m) { return m.indexOf('Check-out') === 0; }));
            lastOut = $out.val();
        });
        $in.add($out).on('input', function () { say($(this), []); });
        constrain();

        var legacySubmit = window.submitBookingForm;
        if (typeof legacySubmit === 'function' && !legacySubmit.mmStayGuarded) {
            var guarded = function () {
                var r = resolve($in.val(), $out.val(), min, allowPast);
                var blocked = r.invalid || r.messages.length > 0;
                if (blocked) {
                    say($in, r.messages.filter(function (m) { return m.indexOf('Check-in') === 0 || m.indexOf('Enter') === 0; }));
                    say($out, r.messages.filter(function (m) { return m.indexOf('Check-out') === 0; }));
                    if (window.toastr) window.toastr.error(r.messages[0]);
                    return;
                }
                return legacySubmit.apply(this, arguments);
            };
            guarded.mmStayGuarded = true;
            window.submitBookingForm = guarded;
        }
    }

    window.MMStayDates = { resolve: resolve, nights: nights, addDays: addDays, init: init };
    $(init);
})(window, window.jQuery);
