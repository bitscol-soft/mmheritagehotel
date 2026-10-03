/* =============================================================================
   mm-ui.js — small shared JS for mm-web components
   -----------------------------------------------------------------------------
   Loaded by <x-mm.scripts /> (a new component added in W1). Handles the
   interactive bits that CSS alone can't cover:

     - mm-modal: open/close, ESC key, backdrop click, focus trap.
     - mm-daterange: quick-range chip click sets the from/to inputs.
     - mm-chip: remove button removes the chip from the DOM.
     - mm-data-table: priority-fold at ≤767px.
     - mm-print-sheet: print button.

   The file is plain ES2017 (no build step). It uses the data-* attribute
   pattern documented in resources/css/ui.css — every component root has a
   `data-mm-<name>` attribute so we can find them in the DOM without coupling
   to class names.

   The script initialises on DOMContentLoaded and after every Blade render
   that might have inserted new components (e.g. when a modal is fetched
   asynchronously). It is idempotent — re-running on the same DOM does not
   double-bind handlers, because every handler consults a dataset flag.
   ============================================================================= */
(function () {
    'use strict';

    // --- mm-modal -----------------------------------------------------------
    // Open via:  mm.openModal('my-modal-id')
    //            <button data-mm-modal-open="my-modal-id">...</button>
    // Close via: <button data-mm-modal-close>...</button>
    //            <div data-mm-modal-close class="mm-modal-backdrop">...</div>
    //            mm.closeModal(el)  or  ESC key while focus is inside.
    function openModal(id) {
        var el = document.getElementById(id);
        if (!el || !el.matches('[data-mm-modal]')) return;
        el.removeAttribute('hidden');
        el.setAttribute('data-open', '');
        // Move focus to the first focusable element in the card.
        var card = el.querySelector('.mm-modal-card');
        if (card) {
            var firstFocusable = card.querySelector(
                'a[href], button, textarea, input, select, [tabindex]:not([tabindex="-1"])'
            );
            if (firstFocusable) firstFocusable.focus();
        }
        document.body.style.overflow = 'hidden';
    }
    function closeModal(el) {
        el = el.closest('[data-mm-modal]') || el;
        if (!el) return;
        el.setAttribute('hidden', '');
        el.removeAttribute('data-open');
        document.body.style.overflow = '';
    }
    function bindModals(root) {
        root = root || document;
        // Open triggers: <* data-mm-modal-open="id">
        var openers = root.querySelectorAll('[data-mm-modal-open]');
        for (var i = 0; i < openers.length; i++) {
            (function (opener) {
                if (opener.dataset.mmModalBound) return;
                opener.dataset.mmModalBound = '1';
                opener.addEventListener('click', function (e) {
                    e.preventDefault();
                    openModal(opener.getAttribute('data-mm-modal-open'));
                });
            })(openers[i]);
        }
        // Close triggers: <* data-mm-modal-close>
        var closers = root.querySelectorAll('[data-mm-modal-close]');
        for (var j = 0; j < closers.length; j++) {
            (function (closer) {
                if (closer.dataset.mmModalBound) return;
                closer.dataset.mmModalBound = '1';
                closer.addEventListener('click', function (e) {
                    e.preventDefault();
                    closeModal(closer);
                });
            })(closers[j]);
        }
        // ESC key — only act if a modal is open.
        if (!document.documentElement.dataset.mmModalEscBound) {
            document.documentElement.dataset.mmModalEscBound = '1';
            document.addEventListener('keydown', function (e) {
                if (e.key !== 'Escape' && e.keyCode !== 27) return;
                var open = document.querySelector('[data-mm-modal][data-open]');
                if (open && !open.hasAttribute('data-persistent')) {
                    closeModal(open);
                }
            });
        }
        // Focus trap: cycle Tab/Shift+Tab inside the open modal.
        document.addEventListener('keydown', function (e) {
            if (e.key !== 'Tab' && e.keyCode !== 9) return;
            var open = document.querySelector('[data-mm-modal][data-open]');
            if (!open) return;
            var card = open.querySelector('.mm-modal-card');
            if (!card) return;
            var focusable = card.querySelectorAll(
                'a[href], button, textarea, input, select, [tabindex]:not([tabindex="-1"])'
            );
            if (!focusable.length) return;
            var first = focusable[0], last = focusable[focusable.length - 1];
            if (e.shiftKey && document.activeElement === first) {
                e.preventDefault();
                last.focus();
            } else if (!e.shiftKey && document.activeElement === last) {
                e.preventDefault();
                first.focus();
            }
        });
    }
    window.mm = window.mm || {};
    window.mm.openModal = openModal;
    window.mm.closeModal = closeModal;

    // --- mm-daterange -------------------------------------------------------
    // Quick-range chip click sets the from/to inputs. The presets are date
    // arithmetic in the user's local timezone; we don't convert to UTC.
    function quickRange(name) {
        var d = new Date();
        d.setHours(0, 0, 0, 0);
        var y = d.getFullYear(), m = d.getMonth(), day = d.getDate();
        function pad(n) { return n < 10 ? '0' + n : '' + n; }
        function fmt(date) { return date.getFullYear() + '-' + pad(date.getMonth() + 1) + '-' + pad(date.getDate()); }
        switch (name) {
            case 'today': return { from: fmt(d), to: fmt(d) };
            case 'tomorrow': { var t = new Date(d); t.setDate(t.getDate() + 1); return { from: fmt(t), to: fmt(t) }; }
            case 'week': {
                // Sunday → Saturday of the current week.
                var start = new Date(d); start.setDate(d.getDate() - d.getDay());
                var end = new Date(start); end.setDate(start.getDate() + 6);
                return { from: fmt(start), to: fmt(end) };
            }
            case 'month': {
                var startM = new Date(y, m, 1);
                var endM = new Date(y, m + 1, 0);
                return { from: fmt(startM), to: fmt(endM) };
            }
            case 'weekend': {
                // Friday → Sunday of the current week.
                var fri = new Date(d); fri.setDate(d.getDate() + (5 - d.getDay()));
                var sun = new Date(fri); sun.setDate(fri.getDate() + 2);
                return { from: fmt(fri), to: fmt(sun) };
            }
            case 'tonight': return { from: fmt(d), to: fmt(d) };
        }
        return null;
    }
    function bindDaterange(root) {
        root = root || document;
        var nodes = root.querySelectorAll('[data-mm-daterange]');
        for (var i = 0; i < nodes.length; i++) {
            (function (node) {
                if (node.dataset.mmDaterangeBound) return;
                node.dataset.mmDaterangeBound = '1';
                var fromInput = node.querySelector('.mm-daterange-from');
                var toInput = node.querySelector('.mm-daterange-to');
                var chips = node.querySelectorAll('[data-mm-quick]');
                for (var c = 0; c < chips.length; c++) {
                    (function (chip) {
                        chip.addEventListener('click', function () {
                            var range = quickRange(chip.getAttribute('data-mm-quick'));
                            if (!range) return;
                            if (fromInput) fromInput.value = range.from;
                            if (toInput) toInput.value = range.to;
                            for (var k = 0; k < chips.length; k++) {
                                chips[k].setAttribute('aria-pressed', 'false');
                            }
                            chip.setAttribute('aria-pressed', 'true');
                        });
                    })(chips[c]);
                }
            })(nodes[i]);
        }
    }

    // --- mm-chip remove -----------------------------------------------------
    function bindChipRemove(root) {
        root = root || document;
        var removes = root.querySelectorAll('[data-mm-chip-remove]');
        for (var i = 0; i < removes.length; i++) {
            (function (btn) {
                if (btn.dataset.mmChipBound) return;
                btn.dataset.mmChipBound = '1';
                btn.addEventListener('click', function () {
                    var chip = btn.closest('.mm-chip');
                    if (chip && chip.parentNode) chip.parentNode.removeChild(chip);
                });
            })(removes[i]);
        }
    }

    // --- mm-data-table priority fold ----------------------------------------
    // At ≤767px, columns with data-priority="2" or "3" are hidden via the
    // CSS rule. To match the right <td> with the right <th>, we set
    // `--mm-priority-N-col` to the 1-based column index on the wrap. We
    // recompute on resize.
    function reflowDataTables() {
        if (window.innerWidth > 767) return;
        var wraps = document.querySelectorAll('[data-mm-data-table]');
        for (var i = 0; i < wraps.length; i++) {
            var wrap = wraps[i];
            var ths = wrap.querySelectorAll('thead th');
            for (var j = 0; j < ths.length; j++) {
                var p = ths[j].getAttribute('data-priority');
                if (p === '2' || p === '3') {
                    wrap.style.setProperty('--mm-priority-' + p + '-col', j + 1);
                }
            }
        }
    }

    // --- mm-print-sheet -----------------------------------------------------
    function bindPrint(root) {
        root = root || document;
        var btns = root.querySelectorAll('[data-mm-print]');
        for (var i = 0; i < btns.length; i++) {
            (function (btn) {
                if (btn.dataset.mmPrintBound) return;
                btn.dataset.mmPrintBound = '1';
                btn.addEventListener('click', function () {
                    window.print();
                });
            })(btns[i]);
        }
    }

    // --- bootstrap -----------------------------------------------------------
    function init(root) {
        bindModals(root);
        bindDaterange(root);
        bindChipRemove(root);
        bindPrint(root);
        reflowDataTables();
    }
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () { init(); });
    } else {
        init();
    }
    // Re-init on resize (priority fold).
    var resizeRaf = null;
    window.addEventListener('resize', function () {
        if (resizeRaf) return;
        resizeRaf = window.requestAnimationFrame(function () {
            resizeRaf = null;
            reflowDataTables();
        });
    });
    // Re-init on Livewire / Turbo / custom events.
    document.addEventListener('mm:init', function (e) {
        init(e.detail || document);
    });
    document.addEventListener('livewire:navigated', function () { init(); });
})();
