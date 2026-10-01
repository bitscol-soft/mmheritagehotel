/* Progressive shell enhancement. No booking, permission or financial logic. */
(function () {
    'use strict';
    var body = document.body;
    var sidebar = document.getElementById('sidebar');
    var toggle = document.getElementById('menu-toggler');
    var menu = document.getElementById('mm-primary-menu');
    if (!body.classList.contains('mm-shell') || !sidebar || !toggle || !menu) return;
    body.classList.add('mm-shell-ready');
    var mobile = window.matchMedia('(max-width: 991px)');
    var backdrop = document.querySelector('.mm-shell-backdrop');
    var filter = document.getElementById('mm-menu-filter');
    var density = document.getElementById('mm-density-toggle');
    var main = document.getElementById('mm-main-content');
    var empty = document.getElementById('mm-menu-empty');
    var collapsed = false;
    var isOpen = false;
    // Preferences are browser-local, not server-side per-user settings.
    function read(key) { try { return localStorage.getItem(key); } catch (e) { return null; } }
    function save(key, value) { try { localStorage.setItem(key, value); } catch (e) {} }
    collapsed = read('mm-shell-collapsed') === '1';
    // Let Ace own nested dropdowns, but not shell visibility or rail dimensions.
    sidebar.classList.remove('menu-min', 'display', 'responsive-min');
    function render() {
        body.classList.toggle('mm-nav-collapsed', !mobile.matches && collapsed);
        body.classList.toggle('mm-nav-open', mobile.matches && isOpen);
        var visible = mobile.matches ? isOpen : !collapsed;
        toggle.setAttribute('aria-expanded', String(visible));
        sidebar.setAttribute('aria-hidden', String(!visible));
        sidebar.inert = !visible;
        if (backdrop) backdrop.hidden = !(mobile.matches && isOpen);
        if (main) main.inert = mobile.matches && isOpen;
    }
    function close(restoreFocus) {
        isOpen = false;
        render();
        if (restoreFocus) toggle.focus();
    }
    toggle.addEventListener('click', function (event) {
        // Stop the competing Ace document-level menu toggler.
        event.preventDefault();
        event.stopPropagation();
        if (mobile.matches) {
            isOpen = !isOpen;
            render();
            if (isOpen && filter) filter.focus();
        } else {
            collapsed = !collapsed;
            save('mm-shell-collapsed', collapsed ? '1' : '0');
            render();
            // Existing Chosen adapters listen for this Ace compatibility event.
            if (window.jQuery) window.jQuery(document).trigger('settings.ace', ['sidebar_collapsed', collapsed]);
            window.dispatchEvent(new Event('resize'));
        }
    });
    document.querySelectorAll('[data-mm-close]').forEach(function (button) {
        button.addEventListener('click', function () { close(true); });
    });
    document.addEventListener('keydown', function (event) {
        if (!(mobile.matches && isOpen)) return;
        if (event.key === 'Escape') { event.preventDefault(); close(true); }
        if (event.key === 'Tab') {
            var focusable = Array.from(sidebar.querySelectorAll('a[href],button,input,select,[tabindex="0"]')).filter(function (el) { return !el.disabled && el.getClientRects().length > 0; });
            if (!focusable.length) return;
            var first = focusable[0], last = focusable[focusable.length - 1];
            if (event.shiftKey && (document.activeElement === first || !sidebar.contains(document.activeElement))) { event.preventDefault(); last.focus(); }
            else if (!event.shiftKey && (document.activeElement === last || !sidebar.contains(document.activeElement))) { event.preventDefault(); first.focus(); }
        }
    });
    var breakpointChanged = function () {
        var focusWasInSidebar = sidebar.contains(document.activeElement);
        close(false);
        if (focusWasInSidebar && (mobile.matches || collapsed)) toggle.focus();
    };
    if (mobile.addEventListener) mobile.addEventListener('change', breakpointChanged);
    else mobile.addListener(breakpointChanged);
    // The footer (breadcrumb, copyright, status) is fixed to the bottom of the screen; publish its height so content and sticky bars clear it.
    var footer = document.querySelector('.mm-shell-footer');
    if (footer) {
        var syncFooter = function () { body.style.setProperty('--mm-footer-h', footer.offsetHeight + 'px'); };
        syncFooter();
        if (window.ResizeObserver) new ResizeObserver(syncFooter).observe(footer);
        else window.addEventListener('resize', syncFooter);
    }
    if (density) {
        function compact(value) {
            body.classList.toggle('mm-compact', value);
            density.setAttribute('aria-pressed', String(value));
        }
        compact(read('mm-shell-compact') === '1');
        density.addEventListener('click', function () {
            var value = !body.classList.contains('mm-compact');
            compact(value); save('mm-shell-compact', value ? '1' : '0');
        });
    }
    var clearSearch = document.getElementById('mm-menu-clear');
    function directLink(li) { return Array.from(li.children).find(function (el) { return el.tagName === 'A'; }); }
    function directSubmenu(li) { return Array.from(li.children).find(function (el) { return el.classList.contains('submenu'); }); }
    // Reflect Ace state in accessible disclosures. Ace remains the click owner.
    var disclosures = [];
    menu.querySelectorAll('li').forEach(function (li, index) {
        var link = directLink(li), sub = directSubmenu(li);
        if (!link || !sub || !link.classList.contains('dropdown-toggle')) return;
        if (!sub.id) sub.id = 'mm-submenu-' + index;
        link.setAttribute('aria-controls', sub.id);
        disclosures.push({ item: li, link: link });
        // Links with href="#" already respond to Enter; add Space without duplicate handlers.
        link.addEventListener('keydown', function (event) {
            if (event.key === ' ') { event.preventDefault(); link.click(); }
        });
    });
    function syncDisclosures() {
        disclosures.forEach(function (entry) {
            var expanded = entry.item.classList.contains('open') || (sidebar.classList.contains('mm-menu-searching') && entry.item.classList.contains('mm-menu-match'));
            entry.link.setAttribute('aria-expanded', String(expanded));
        });
    }
    new MutationObserver(syncDisclosures).observe(menu, { subtree: true, attributes: true, attributeFilter: ['class'] });
    syncDisclosures();
    if (clearSearch && filter) clearSearch.addEventListener('click', function () {
        filter.value = '';
        filter.dispatchEvent(new Event('input'));
        filter.focus();
    });
    // Only search links already rendered by the permission-filtered sidebar.
    if (filter) filter.addEventListener('input', function () {
        var query = filter.value.toLowerCase().trim();
        if (clearSearch) clearSearch.hidden = !query;
        var items = Array.from(menu.querySelectorAll('li'));
        items.forEach(function (li) { li.classList.remove('mm-menu-hidden', 'mm-menu-match'); });
        sidebar.classList.toggle('mm-menu-searching', !!query);
        if (!query) { if (empty) empty.hidden = true; syncDisclosures(); return; }
        var matches = 0;
        items.forEach(function (li) {
            var link = directLink(li);
            if (!link || link.textContent.toLowerCase().indexOf(query) < 0) return;
            matches++;
            li.classList.add('mm-menu-match');
            li.querySelectorAll('li').forEach(function (child) { child.classList.add('mm-menu-match'); });
            var parent = li.parentElement.closest('li');
            while (parent && menu.contains(parent)) { parent.classList.add('mm-menu-match'); parent = parent.parentElement.closest('li'); }
        });
        items.forEach(function (li) { li.classList.toggle('mm-menu-hidden', !li.classList.contains('mm-menu-match')); });
        if (empty) empty.hidden = matches !== 0;
        syncDisclosures();
    });
    // Match path plus query so setup Purpose/Platform links do not both look current.
    // Leave server-rendered permission and active conditions intact.
    function queryKey(url) {
        return Array.from(url.searchParams.entries()).sort(function (a, b) { return (a[0] + '=' + a[1]).localeCompare(b[0] + '=' + b[1]); }).map(function (pair) { return JSON.stringify(pair); }).join('&');
    }
    var currentUrl = new URL(location.href);
    menu.querySelectorAll('a[href]').forEach(function (link) {
        if (link.getAttribute('href') === '#') return;
        var url;
        try { url = new URL(link.href, window.location.href); } catch (e) { return; }
        if (url.origin === location.origin && url.pathname === location.pathname && !url.hash && queryKey(url) === queryKey(currentUrl)) link.setAttribute('aria-current', 'page');
    });
    syncDisclosures();
    render();
}());
