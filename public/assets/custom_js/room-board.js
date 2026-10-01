/* Dashboard room board: collapsible categories, room cards and the details/booking drawer.
 * Presentation only. Selection uses the same add/remove endpoints as the previous board and
 * submits the existing #booking-form, so booking creation stays server-owned. */
(function () {
    'use strict';
    var board = document.getElementById('mmb');
    if (!board) return;
    var drawer = document.getElementById('mmb-drawer');
    var backdrop = board.querySelector('.mmb-backdrop');
    var form = document.getElementById('booking-form');
    var storageKey = 'mm-board-collapsed';
    var opener = null;
    var current = null;
    var busy = false;
    var labels = { available: 'Available', dirty: 'Dirty', maintenance: 'Maintenance', cart: 'In booking cart' };

    function read() { try { return JSON.parse(localStorage.getItem(storageKey) || '[]'); } catch (e) { return []; } }
    function save(list) { try { localStorage.setItem(storageKey, JSON.stringify(list)); } catch (e) { /* storage may be blocked */ } }
    function notify(kind, message) {
        if (window.toastr && message) window.toastr[kind](message);
    }
    function el(tag, className, text) {
        var node = document.createElement(tag);
        if (className) node.className = className;
        if (text !== undefined) node.textContent = text;
        return node;
    }

    /* ---------- Category groups ---------- */
    function setGroup(group, expanded) {
        var toggle = group.querySelector('.mmb-group-toggle');
        var body = group.querySelector('.mmb-group-body');
        toggle.setAttribute('aria-expanded', String(expanded));
        body.hidden = !expanded;
        group.classList.toggle('is-collapsed', !expanded);
    }
    function persist() {
        save(Array.prototype.filter.call(board.querySelectorAll('.mmb-group'), function (g) { return g.classList.contains('is-collapsed'); })
            .map(function (g) { return g.getAttribute('data-category'); }));
    }
    var collapsedIds = read();
    board.querySelectorAll('.mmb-group').forEach(function (group) {
        setGroup(group, collapsedIds.indexOf(group.getAttribute('data-category')) < 0);
    });
    board.addEventListener('click', function (event) {
        var toggle = event.target.closest('.mmb-group-toggle');
        if (toggle) {
            var group = toggle.closest('.mmb-group');
            setGroup(group, toggle.getAttribute('aria-expanded') !== 'true');
            persist();
        }
        if (event.target.closest('[data-mmb-expand-all]')) {
            board.querySelectorAll('.mmb-group').forEach(function (g) { setGroup(g, true); });
            persist();
        }
        if (event.target.closest('[data-mmb-collapse-all]')) {
            board.querySelectorAll('.mmb-group').forEach(function (g) { setGroup(g, false); });
            persist();
        }
    });

    /* ---------- Card state ---------- */
    function cardData(card) { try { return JSON.parse(card.getAttribute('data-room')); } catch (e) { return {}; } }
    function stateText(card) {
        var chip = card.querySelector('.mmb-chip-text');
        return chip ? chip.textContent : '';
    }
    function applyState(card, state) {
        var label = labels[state] || labels.available;
        card.setAttribute('data-state', state);
        card.parentNode.setAttribute('data-state', state);
        var chip = card.querySelector('.mmb-chip');
        chip.setAttribute('data-state', state);
        card.querySelector('.mmb-chip-text').textContent = label;
        var data = cardData(card);
        card.setAttribute('aria-label', 'Room ' + data.number + ', ' + data.bed_label + ', ' + label + (card.classList.contains('is-selected') ? ', selected' : '') + '. Open details.');
        if (current === card) fillDrawer(card);
    }
    // Existing updateStatus() edits a hidden legacy proxy; mirror that state onto the card.
    board.querySelectorAll('.mmb-tile').forEach(function (tile) {
        var proxy = tile.querySelector('.mmb-proxy');
        var card = tile.querySelector('.mmb-card');
        if (!proxy) return;
        new MutationObserver(function () {
            var state = proxy.classList.contains('inverse') ? 'dirty' : proxy.classList.contains('orange') ? 'maintenance' : proxy.classList.contains('store') ? 'cart' : 'available';
            applyState(card, state);
        }).observe(proxy, { attributes: true, attributeFilter: ['class'] });
    });

    /* ---------- Selection ---------- */
    var previousOverflow = '';
    function selectedCards() { return Array.prototype.slice.call(board.querySelectorAll('.mmb-card.is-selected')); }
    function updateSummary() {
        var cards = selectedCards();
        var total = cards.reduce(function (sum, card) { var rate = parseFloat(card.getAttribute('data-rate')); return sum + (isNaN(rate) ? 0 : rate); }, 0);
        var box = board.querySelector('.mmb-selection');
        box.hidden = cards.length === 0;
        box.querySelector('.mmb-sel-count').textContent = cards.length;
        box.querySelector('.mmb-sel-total').textContent = total.toLocaleString();
        box.querySelector('.mmb-sel-total-wrap').hidden = total === 0;
    }
    function mark(card, selected) {
        card.classList.toggle('is-selected', selected);
        card.setAttribute('aria-label', card.getAttribute('aria-label').replace(', selected', '').replace('. Open details.', (selected ? ', selected' : '') + '. Open details.'));
        updateSummary();
        if (current) renderActions();
    }
    function selectRoom(card) {
        var data = cardData(card);
        return new Promise(function (resolve) {
            if (!window.jQuery) { resolve(false); return; }
            window.jQuery.ajax({
                headers: { 'X-CSRF-TOKEN': window.jQuery('meta[name="csrf-token"]').attr('content') },
                type: 'post', url: board.getAttribute('data-add-url'),
                data: { category_id: data.category_id, room_id: data.room_id, date: window.jQuery('#available_date').val() },
                success: function (response) {
                    if (response.status) { notify('success', response.data); mark(card, true); resolve(true); }
                    else { notify('warning', response.data); resolve(false); }
                },
                error: function () { notify('error', 'The room could not be added. Please try again.'); resolve(false); }
            });
        });
    }
    function unselectRoom(card) {
        var data = cardData(card);
        return new Promise(function (resolve) {
            window.jQuery.ajax({
                url: board.getAttribute('data-remove-url'), method: 'get', data: { product_id: data.room_id },
                success: function (response) { notify('warning', response.data); mark(card, false); resolve(true); },
                error: function () { notify('error', 'The room could not be removed. Please try again.'); resolve(false); }
            });
        });
    }
    function withBusy(task) {
        if (busy) return;
        busy = true; drawer.classList.add('is-busy');
        task().then(function () { busy = false; drawer.classList.remove('is-busy'); }, function () { busy = false; drawer.classList.remove('is-busy'); });
    }
    function submitBooking(value) {
        var button = form.querySelector('button[name="submit"][value="' + value + '"]');
        if (form.requestSubmit) form.requestSubmit(button); else button.click();
    }

    /* ---------- Drawer ---------- */
    function row(list, label, value) {
        if (value === undefined || value === null || value === '') return;
        list.appendChild(el('dt', '', label));
        list.appendChild(el('dd', '', value));
    }
    function fillDrawer(card) {
        var data = cardData(card);
        var state = card.getAttribute('data-state');
        document.getElementById('mmb-drawer-title').textContent = 'Room ' + data.number;
        document.getElementById('mmb-drawer-category').textContent = data.category;
        var chip = document.getElementById('mmb-drawer-chip');
        chip.setAttribute('data-state', state);
        chip.querySelector('.mmb-chip-text').textContent = stateText(card);
        var hk = document.getElementById('mmb-drawer-hk');
        hk.hidden = !data.housekeeping;
        hk.textContent = data.housekeeping ? 'Housekeeping: ' + data.housekeeping : '';
        document.getElementById('mmb-drawer-bed-icon').innerHTML = card.querySelector('.mmb-bed').innerHTML;
        document.getElementById('mmb-drawer-bed-label').textContent = data.bed_detail ? data.bed_label + ' · ' + data.bed_detail : data.bed_label;
        var list = document.getElementById('mmb-drawer-list');
        list.textContent = '';
        row(list, 'Beds', data.beds);
        row(list, 'Max guests', data.max_guests);
        row(list, 'Category capacity', data.capacity);
        row(list, 'Can sleep', data.can_sleep);
        row(list, 'Room size', data.size ? data.size + ' sqft' : '');
        row(list, 'Smoking', data.smoking);
        row(list, 'Room rate', data.rate);
        var description = document.getElementById('mmb-drawer-description');
        description.hidden = !data.description;
        description.textContent = data.description || '';
        var guest = document.getElementById('mmb-drawer-guest');
        var guestList = document.getElementById('mmb-drawer-guest-list');
        guestList.textContent = '';
        guest.hidden = !data.occupied;
        if (data.occupied) {
            if (data.guest) {
                row(guestList, 'Guest', data.guest.name);
                row(guestList, 'Phone', data.guest.phone);
                row(guestList, 'Passport / NID', data.guest.passport);
                row(guestList, 'Check-in', data.guest.check_in);
                row(guestList, 'Check-out', data.guest.check_out);
                row(guestList, 'Note', data.guest.note);
            } else {
                guestList.appendChild(el('dd', 'mmb-muted', 'Guest details are not available for the selected date.'));
            }
        }
        var stay = document.getElementById('available_date');
        document.getElementById('mmb-drawer-stay').textContent = !data.occupied && stay && stay.value ? 'Stay dates: ' + stay.value : '';
        renderActions();
    }
    function button(label, className, iconName, handler) {
        var node = el('button', className);
        node.type = 'button';
        if (iconName) { var icon = el('i', 'fa ' + iconName); icon.setAttribute('aria-hidden', 'true'); node.appendChild(icon); node.appendChild(document.createTextNode(' ')); }
        node.appendChild(document.createTextNode(label));
        node.addEventListener('click', handler);
        return node;
    }
    function renderActions() {
        var actions = document.getElementById('mmb-drawer-actions');
        actions.textContent = '';
        if (!current) return;
        var data = cardData(current);
        var state = current.getAttribute('data-state');
        if (data.occupied) {
            if (data.guest && data.guest.check_label) {
                actions.appendChild(button(data.guest.check_label, 'mmb-btn mmb-btn-primary', 'fa-clock-o', function () {
                    if (typeof window.checkOut === 'function') window.checkOut(data.guest.check_url, data.guest.check_label, String(data.guest.booking_id));
                }));
            }
            if (data.guest) {
                var open = el('a', 'mmb-btn', '');
                open.href = data.guest.booking_url; open.target = '_blank'; open.rel = 'noopener';
                open.innerHTML = '<i class="fa fa-external-link" aria-hidden="true"></i> Open booking';
                actions.appendChild(open);
                var migrate = el('a', 'mmb-btn', '');
                migrate.href = data.guest.migrate_url; migrate.target = '_blank'; migrate.rel = 'noopener';
                migrate.innerHTML = '<i class="fa fa-adjust" aria-hidden="true"></i> Migrate';
                actions.appendChild(migrate);
            }
            return;
        }
        if (state === 'cart') {
            actions.appendChild(el('p', 'mmb-muted', 'This room is already in a booking cart.'));
            return;
        }
        var isSelected = current.classList.contains('is-selected');
        var count = selectedCards().length + (isSelected ? 0 : 1);
        var countText = count + ' room' + (count === 1 ? '' : 's');
        actions.appendChild(button('Book ' + countText, 'mmb-btn mmb-btn-primary', 'fa-send', function () {
            withBusy(function () { return (isSelected ? Promise.resolve(true) : selectRoom(current)).then(function (ok) { if (ok) submitBooking('book'); }); });
        }));
        actions.appendChild(button('Reserve ' + countText, 'mmb-btn', 'fa-bookmark', function () {
            withBusy(function () { return (isSelected ? Promise.resolve(true) : selectRoom(current)).then(function (ok) { if (ok) submitBooking('reserve'); }); });
        }));
        actions.appendChild(button(isSelected ? 'Remove from selection' : 'Add to selection', 'mmb-btn', isSelected ? 'fa-minus' : 'fa-plus', function () {
            withBusy(function () { return isSelected ? unselectRoom(current) : selectRoom(current); });
        }));
        var proxyTrigger = current.parentNode.querySelector('.mmb-proxy-trigger');
        if (proxyTrigger) {
            actions.appendChild(button('Change housekeeping status', 'mmb-btn mmb-btn-quiet', 'fa-exchange', function () { proxyTrigger.click(); }));
        }
    }
    function openDrawer(card) {
        opener = card;
        current = card;
        fillDrawer(card);
        drawer.classList.add('is-open');
        drawer.setAttribute('aria-hidden', 'false');
        drawer.inert = false;
        backdrop.hidden = false;
        previousOverflow = document.body.style.overflow; document.body.style.overflow = 'hidden';
        drawer.querySelector('[data-mmb-close]').focus();
    }
    function closeDrawer() {
        if (!drawer.classList.contains('is-open')) return;
        drawer.classList.remove('is-open');
        drawer.setAttribute('aria-hidden', 'true');
        drawer.inert = true;
        backdrop.hidden = true;
        document.body.style.overflow = previousOverflow;
        current = null;
        if (opener && document.contains(opener)) opener.focus();
    }
    drawer.inert = true;
    board.addEventListener('click', function (event) {
        var card = event.target.closest('.mmb-card');
        if (card) openDrawer(card);
        if (event.target.closest('[data-mmb-close]') || event.target === backdrop) closeDrawer();
    });
    document.addEventListener('keydown', function (event) {
        if (!drawer.classList.contains('is-open') || document.querySelector('.swal2-container')) return;
        if (event.key === 'Escape') { event.preventDefault(); closeDrawer(); return; }
        if (event.key !== 'Tab') return;
        var focusable = Array.prototype.filter.call(drawer.querySelectorAll('a[href],button:not([disabled])'), function (node) { return node.getClientRects().length > 0; });
        if (!focusable.length) return;
        var first = focusable[0], last = focusable[focusable.length - 1];
        if (event.shiftKey && (document.activeElement === first || !drawer.contains(document.activeElement))) { event.preventDefault(); last.focus(); }
        else if (!event.shiftKey && (document.activeElement === last || !drawer.contains(document.activeElement))) { event.preventDefault(); first.focus(); }
    });
    updateSummary();
}());
