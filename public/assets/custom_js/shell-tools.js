/* Shell header/footer enhancements: command palette, shortcuts help, theme, full screen, footer status.
   Progressive: every control is hidden until it is known to work, and nothing here touches bookings,
   permissions or money. Screens listed in the palette come only from the permission-filtered sidebar. */
(function () {
    'use strict';
    var body = document.body;
    if (!body.classList.contains('mm-shell')) return;

    function read(key) { try { return localStorage.getItem(key); } catch (e) { return null; } }
    function save(key, value) { try { localStorage.setItem(key, value); } catch (e) {} }
    function all(selector, root) { return Array.prototype.slice.call((root || document).querySelectorAll(selector)); }
    function isTyping(target) {
        return !!target && (target.isContentEditable || /^(INPUT|TEXTAREA|SELECT)$/.test(target.tagName));
    }
    var isMac = /Mac|iPhone|iPad/.test(navigator.platform || navigator.userAgent);
    var hasDialog = typeof HTMLDialogElement === 'function' && typeof document.createElement('dialog').showModal === 'function';

    /* ---------- Theme ---------- */
    function setTheme(dark, persist) {
        body.classList.toggle('mm-dark', dark);
        all('[data-mm-theme-toggle]').forEach(function (button) {
            var label = dark ? 'Light theme' : 'Dark theme';
            button.setAttribute('aria-pressed', String(dark));
            button.setAttribute('aria-label', label);
            button.title = label;
            var icon = button.querySelector('i');
            if (icon) icon.className = 'fa ' + (dark ? 'fa-sun-o' : 'fa-moon-o');
        });
        if (persist) save('mm-theme', dark ? 'dark' : 'light');
    }
    function toggleTheme() { setTheme(!body.classList.contains('mm-dark'), true); }
    all('[data-mm-theme-toggle]').forEach(function (button) { button.hidden = false; button.addEventListener('click', toggleTheme); });
    setTheme(read('mm-theme') === 'dark', false);

    /* ---------- Full screen ---------- */
    var canFullscreen = !!(document.fullscreenEnabled && document.documentElement.requestFullscreen);
    function toggleFullscreen() {
        if (!canFullscreen) return;
        if (document.fullscreenElement) document.exitFullscreen(); else document.documentElement.requestFullscreen().catch(function () {});
    }
    all('[data-mm-fullscreen]').forEach(function (button) {
        button.hidden = !canFullscreen;
        button.addEventListener('click', toggleFullscreen);
    });
    document.addEventListener('fullscreenchange', function () {
        var on = !!document.fullscreenElement;
        all('[data-mm-fullscreen]').forEach(function (button) {
            button.setAttribute('aria-pressed', String(on));
            button.setAttribute('aria-label', on ? 'Exit full screen' : 'Full screen');
            button.title = on ? 'Exit full screen' : 'Full screen';
            var icon = button.querySelector('i');
            if (icon) icon.className = 'fa ' + (on ? 'fa-compress' : 'fa-expand');
        });
    });

    /* ---------- Dialog plumbing (native <dialog>) ---------- */
    var palette = document.getElementById('mm-palette');
    var help = document.getElementById('mm-shortcuts');
    var openers = all('[data-mm-palette-open],[data-mm-shortcuts-open]');
    openers.forEach(function (node) { node.hidden = !hasDialog; });
    all('[data-mm-mod-key]').forEach(function (node) { node.textContent = isMac ? '⌘ K' : 'Ctrl K'; });
    function openDialog(dialog) {
        if (!hasDialog || !dialog || dialog.open) return;
        all('dialog[open]').forEach(function (other) { other.close(); });
        dialog.showModal();
    }
    all('[data-mm-dialog-close]').forEach(function (button) {
        button.addEventListener('click', function () { var dialog = button.closest('dialog'); if (dialog) dialog.close(); });
    });
    all('dialog.mm-dialog').forEach(function (dialog) {
        // Click on the backdrop (the dialog element itself) closes it.
        dialog.addEventListener('mousedown', function (event) { if (event.target === dialog) dialog.close(); });
    });
    all('[data-mm-shortcuts-open]').forEach(function (button) { button.addEventListener('click', function () { openDialog(help); }); });

    /* ---------- Command palette ---------- */
    var input = document.getElementById('mm-palette-input');
    var list = document.getElementById('mm-palette-list');
    var empty = document.getElementById('mm-palette-empty');
    var status = document.getElementById('mm-palette-status');
    var active = -1;
    var shown = [];
    var screens = null;

    function menuEntries() {
        var menu = document.getElementById('mm-primary-menu');
        var entries = [], seen = {};
        if (!menu) return entries;
        all('a[href]', menu).forEach(function (link) {
            var href = link.getAttribute('href');
            if (!href || href === '#' || /^javascript:/i.test(href)) return;
            var trail = [], li = link.closest('li');
            while (li && menu.contains(li)) {
                var direct = Array.prototype.find.call(li.children, function (child) { return child.tagName === 'A'; });
                if (direct) trail.unshift(direct.textContent.replace(/\s+/g, ' ').trim());
                li = li.parentElement && li.parentElement.closest('li');
            }
            trail = trail.filter(Boolean);
            if (!trail.length || seen[link.href]) return;
            seen[link.href] = true;
            entries.push({ group: 'Screens', label: trail[trail.length - 1], detail: trail.slice(0, -1).join(' › '), url: link.href, icon: 'fa-angle-right', key: (trail.join(' ')).toLowerCase() });
        });
        return entries;
    }
    function actions() {
        var items = [];
        var booking = document.querySelector('[data-mm-action="new-booking"]');
        if (booking) items.push({ group: 'Actions', label: 'New booking', detail: 'Start a booking', url: booking.href, icon: 'fa-plus', key: 'new booking create reservation' });
        items.push(
            { group: 'Actions', label: body.classList.contains('mm-dark') ? 'Switch to light theme' : 'Switch to dark theme', detail: 'Appearance', run: toggleTheme, icon: 'fa-adjust', key: 'theme dark light appearance' },
            { group: 'Actions', label: 'Toggle compact spacing', detail: 'Appearance', run: function () { var button = document.getElementById('mm-density-toggle'); if (button) button.click(); }, icon: 'fa-compress', key: 'compact density spacing' },
            { group: 'Actions', label: 'Show or hide sidebar', detail: 'Layout', run: function () { var button = document.getElementById('menu-toggler'); if (button) button.click(); }, icon: 'fa-bars', key: 'sidebar menu navigation toggle' },
            { group: 'Actions', label: 'Keyboard shortcuts', detail: 'Help', run: function () { openDialog(help); }, icon: 'fa-keyboard-o', key: 'keyboard shortcuts help' }
        );
        if (canFullscreen) items.push({ group: 'Actions', label: document.fullscreenElement ? 'Exit full screen' : 'Enter full screen', detail: 'Layout', run: toggleFullscreen, icon: 'fa-expand', key: 'fullscreen full screen' });
        return items;
    }
    function recents() {
        var stored = [];
        try { stored = JSON.parse(read('mm-recent') || '[]'); } catch (e) { stored = []; }
        return stored.filter(function (item) { return item && item.url && item.label; }).map(function (item) {
            return { group: 'Recent', label: item.label, detail: 'Visited screen', url: item.url, icon: 'fa-history', key: item.label.toLowerCase() };
        });
    }
    function rememberVisit() {
        var crumb = document.querySelector('.mm-crumbs [aria-current="page"]');
        var label = (crumb && crumb.textContent.trim()) || document.title.replace(/\s*[-|].*$/, '').trim();
        var url = location.pathname + location.search;
        if (!label || /^\/?(home)?$/.test(location.pathname)) return;
        var stored = [];
        try { stored = JSON.parse(read('mm-recent') || '[]'); } catch (e) { stored = []; }
        stored = stored.filter(function (item) { return item && item.url !== location.origin + url; });
        stored.unshift({ label: label, url: location.origin + url });
        save('mm-recent', JSON.stringify(stored.slice(0, 5)));
    }
    function score(item, tokens) {
        var hay = item.key + ' ' + (item.detail || '').toLowerCase();
        var total = 0;
        for (var i = 0; i < tokens.length; i++) {
            var at = hay.indexOf(tokens[i]);
            if (at < 0) return -1;
            total += at === 0 ? 3 : (hay.charAt(at - 1) === ' ' ? 2 : 1);
        }
        return total + (item.label.toLowerCase().indexOf(tokens[0]) === 0 ? 2 : 0);
    }
    function render() {
        var query = input.value.toLowerCase().trim();
        var pool = actions().concat(screens);
        if (!query) {
            shown = recents().concat(actions().slice(0, 5));
            if (!shown.length) shown = screens.slice(0, 8);
        } else {
            var tokens = query.split(/\s+/);
            shown = pool.map(function (item) { return { item: item, score: score(item, tokens) }; })
                .filter(function (row) { return row.score >= 0; })
                .sort(function (a, b) { return b.score - a.score || a.item.label.localeCompare(b.item.label); })
                .slice(0, 30).map(function (row) { return row.item; });
        }
        list.textContent = '';
        var lastGroup = '';
        shown.forEach(function (item, index) {
            if (item.group !== lastGroup) {
                lastGroup = item.group;
                var header = document.createElement('li');
                header.className = 'mm-palette-group';
                header.setAttribute('role', 'presentation');
                header.textContent = item.group;
                list.appendChild(header);
            }
            var row = document.createElement('li');
            row.id = 'mm-palette-opt-' + index;
            row.className = 'mm-palette-option';
            row.setAttribute('role', 'option');
            row.setAttribute('aria-selected', 'false');
            row.setAttribute('data-index', index);
            var icon = document.createElement('i');
            icon.className = 'fa ' + item.icon; icon.setAttribute('aria-hidden', 'true');
            var text = document.createElement('span'); text.className = 'mm-palette-label'; text.textContent = item.label;
            row.appendChild(icon); row.appendChild(text);
            if (item.detail) { var detail = document.createElement('span'); detail.className = 'mm-palette-detail'; detail.textContent = item.detail; row.appendChild(detail); }
            list.appendChild(row);
        });
        empty.hidden = shown.length !== 0;
        status.textContent = shown.length ? shown.length + (shown.length === 1 ? ' result' : ' results') : 'No results';
        setActive(shown.length ? 0 : -1);
    }
    function setActive(index) {
        var rows = all('.mm-palette-option', list);
        rows.forEach(function (row) { row.setAttribute('aria-selected', 'false'); row.classList.remove('is-active'); });
        active = index;
        if (index < 0 || !rows[index]) { input.removeAttribute('aria-activedescendant'); return; }
        rows[index].setAttribute('aria-selected', 'true');
        rows[index].classList.add('is-active');
        input.setAttribute('aria-activedescendant', rows[index].id);
        if (rows[index].scrollIntoView) rows[index].scrollIntoView({ block: 'nearest' });
    }
    function choose(index) {
        var item = shown[index];
        if (!item) return;
        palette.close();
        if (item.run) item.run(); else if (item.url) window.location.href = item.url;
    }
    function openPalette() {
        if (!palette || !hasDialog) return;
        if (!screens) screens = menuEntries();
        openDialog(palette);
        input.value = '';
        render();
        input.focus();
    }
    if (palette && input) {
        all('[data-mm-palette-open]').forEach(function (button) { button.addEventListener('click', openPalette); });
        input.addEventListener('input', render);
        input.addEventListener('keydown', function (event) {
            if (event.key === 'ArrowDown') { event.preventDefault(); if (shown.length) setActive((active + 1) % shown.length); }
            else if (event.key === 'ArrowUp') { event.preventDefault(); if (shown.length) setActive((active - 1 + shown.length) % shown.length); }
            else if (event.key === 'Home') { event.preventDefault(); setActive(shown.length ? 0 : -1); }
            else if (event.key === 'End') { event.preventDefault(); setActive(shown.length - 1); }
            else if (event.key === 'Enter') { event.preventDefault(); choose(active); }
        });
        list.addEventListener('click', function (event) {
            var row = event.target.closest('.mm-palette-option');
            if (row) choose(parseInt(row.getAttribute('data-index'), 10));
        });
        list.addEventListener('mousemove', function (event) {
            var row = event.target.closest('.mm-palette-option');
            if (row) { var index = parseInt(row.getAttribute('data-index'), 10); if (index !== active) setActive(index); }
        });
    }
    rememberVisit();

    /* ---------- Global shortcuts ---------- */
    document.addEventListener('keydown', function (event) {
        if (event.defaultPrevented) return;
        var key = event.key;
        if ((event.ctrlKey || event.metaKey) && !event.altKey && !event.shiftKey && key.toLowerCase() === 'k') {
            if (!hasDialog) return;
            event.preventDefault();
            if (palette.open) palette.close(); else openPalette();
            return;
        }
        if (event.ctrlKey || event.metaKey || event.altKey || isTyping(event.target) || document.querySelector('dialog[open], .swal2-container')) return;
        if (key === '?' || (key === '/' && event.shiftKey)) { event.preventDefault(); openDialog(help); }
        else if (key === '/') { event.preventDefault(); openPalette(); }
        else if (key === '[') { var toggle = document.getElementById('menu-toggler'); if (toggle) toggle.click(); }
    });

    /* ---------- Footer status ---------- */
    var clock = document.getElementById('mm-clock');
    if (clock) {
        var offset = Date.parse(clock.getAttribute('data-server-time')) - Date.now();
        var zone = clock.getAttribute('data-timezone');
        var formatter;
        try { formatter = new Intl.DateTimeFormat('en-GB', { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false, timeZone: zone }); }
        catch (e) { formatter = null; }
        if (formatter && !isNaN(offset)) {
            var tick = function () { clock.textContent = formatter.format(new Date(Date.now() + offset)); };
            tick();
            setInterval(tick, 1000);
        }
    }
    var online = document.getElementById('mm-online');
    var onlineText = document.getElementById('mm-online-text');
    function syncOnline() {
        var up = navigator.onLine !== false;
        if (!online) return;
        online.setAttribute('data-online', String(up));
        onlineText.textContent = up ? 'Online' : 'Offline';
    }
    window.addEventListener('online', syncOnline);
    window.addEventListener('offline', syncOnline);
    syncOnline();
    var sync = document.getElementById('mm-sync');
    // Counted from when this page finished loading; later successful AJAX responses reset it.
    var lastSync = Date.now();
    function relative() {
        if (!sync) return;
        var seconds = Math.max(0, Math.round((Date.now() - lastSync) / 1000));
        var text = seconds < 45 ? 'just now' : seconds < 3600 ? Math.round(seconds / 60) + ' min ago' : Math.round(seconds / 3600) + ' h ago';
        sync.textContent = text;
        sync.setAttribute('datetime', new Date(lastSync).toISOString());
        sync.title = new Date(lastSync).toLocaleString();
    }
    function markSync() { lastSync = Date.now(); relative(); }
    if (sync) {
        relative();
        setInterval(relative, 30000);
        if (window.jQuery) window.jQuery(document).ajaxSuccess(markSync);
    }
}());
