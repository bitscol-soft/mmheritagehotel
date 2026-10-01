/* Stay date range for the room board (daterangepicker 2.1.17 + moment).
 * - past check-in dates are blocked (data-allow-past="1" opts out), measured from the business date
 *   (data-business-date, Y-m-d, rendered by the server) rather than the browser clock;
 * - check-out is always at least one night after check-in;
 * - typed text is validated before the board form is submitted;
 * - quick chips use the same commit path.
 * Messages are written with textContent only. */
(function (window, $) {
    'use strict';
    if (!$ || typeof moment === 'undefined') return;
    var FORMAT = 'MM/DD/YYYY', SEP = ' - ';

    function businessDate(el) {
        var raw = el && el.getAttribute ? el.getAttribute('data-business-date') : null;
        var m = raw ? moment(raw, 'YYYY-MM-DD', true) : null;
        return (m && m.isValid() ? m : moment()).startOf('day');
    }
    function parse(text) {
        var parts = String(text || '').trim().split(/\s+-\s+/);
        if (parts.length !== 2) return null;
        var start = moment(parts[0].trim(), FORMAT, true), end = moment(parts[1].trim(), FORMAT, true);
        return start.isValid() && end.isValid() ? { start: start, end: end } : null;
    }
    // Returns { start, end, adjusted } or { error }.
    function normalize(start, end, min, allowPast) {
        start = moment(start).startOf('day');
        end = moment(end).startOf('day');
        if (!allowPast && start.isBefore(min)) return { error: 'Check-in cannot be before the business date (' + min.format('DD MMM YYYY') + ').' };
        var adjusted = false;
        if (!end.isAfter(start)) { end = start.clone().add(1, 'days'); adjusted = true; }
        return { start: start, end: end, adjusted: adjusted };
    }
    function format(start, end) { return start.format(FORMAT) + SEP + end.format(FORMAT); }

    function init(input) {
        var $input = $(input);
        if (!input || $input.data('daterangepicker') || typeof $input.daterangepicker !== 'function') return null;
        var min = businessDate(input), allowPast = input.getAttribute('data-allow-past') === '1';
        var current = parse(input.value);
        var opts = {
            autoUpdateInput: false, autoApply: true, showDropdowns: true, linkedCalendars: true,
            opens: 'right', drops: 'auto', locale: { format: FORMAT, separator: SEP }
        };
        if (!allowPast) opts.minDate = min;
        if (current) {
            var from = !allowPast && current.start.isBefore(min) ? min.clone() : current.start;
            opts.startDate = from;
            opts.endDate = current.end.isAfter(from) ? current.end : from.clone().add(1, 'days');
        }
        $input.daterangepicker(opts);
        var picker = $input.data('daterangepicker');

        var note = document.createElement('p');
        note.className = 'mm-range-error';
        note.id = (input.id || 'stay-range') + '-error';
        note.setAttribute('role', 'alert');
        note.hidden = true;
        input.parentNode.appendChild(note);
        input.setAttribute('autocomplete', 'off');

        function show(message) {
            note.textContent = message; note.hidden = false;
            input.setAttribute('aria-invalid', 'true'); input.setAttribute('aria-describedby', note.id);
        }
        function clear() {
            note.textContent = ''; note.hidden = true;
            input.removeAttribute('aria-invalid'); input.removeAttribute('aria-describedby');
        }
        function commit(start, end) {
            var result = normalize(start, end, min, allowPast);
            if (result.error) { show(result.error); return false; }
            clear();
            input.value = format(result.start, result.end);
            picker.setStartDate(result.start); picker.setEndDate(result.end);
            $input.closest('form').trigger('submit');
            return true;
        }
        $input.on('apply.daterangepicker', function (event, p) { commit(p.startDate, p.endDate); });
        // Keep the whole calendar reachable when the input sits near the bottom of the screen.
        $input.on('show.daterangepicker', function () {
            window.requestAnimationFrame(function () {
                var rect = picker.container[0].getBoundingClientRect();
                if (rect.bottom > window.innerHeight) window.scrollBy(0, Math.min(rect.bottom - window.innerHeight + 16, rect.top - 8));
            });
        });
        $input.on('input', clear);
        $input.on('change', function () {
            var typed = parse(input.value);
            if (typed && typed.end.isAfter(typed.start)) { picker.setStartDate(typed.start); picker.setEndDate(typed.end); }
        });
        $input.closest('form').on('submit', function (event) {
            var typed = parse(input.value);
            if (!typed) { event.preventDefault(); show('Enter dates as MM/DD/YYYY - MM/DD/YYYY.'); input.focus(); return; }
            var result = normalize(typed.start, typed.end, min, allowPast);
            if (result.error) { event.preventDefault(); show(result.error); input.focus(); return; }
            input.value = format(result.start, result.end);
        });
        return { picker: picker, commit: commit, min: min };
    }

    function chip(mode, input) {
        input = input || $('input[name="booking_date"]').first()[0];
        var api = input && $(input).data('mmStay');
        if (!api) return false;
        var start = api.min.clone(), end = start.clone().add(1, 'days');
        if (mode === 'week') end = start.clone().add(6, 'days');
        if (mode === 'weekend') {
            start = api.min.clone().isoWeekday(6);
            if (start.isBefore(api.min)) start.add(7, 'days');
            end = start.clone().add(2, 'days');
        }
        api.commit(start, end);
        return true;
    }

    // Delegated on document: the chips render before this script loads, so inline binding cannot rely on load order.
    $(document).on('click', '.mmb-quick .mmb-pill, .board-quick-dates .btn', function () {
        chip($(this).data('mode'));
    });

    var base = init;
    window.MMStayRange = {
        init: function (input) { var api = base(input); if (api) $(input).data('mmStay', api); return api; },
        chip: chip, parse: parse, normalize: normalize, businessDate: businessDate
    };
})(window, window.jQuery);
