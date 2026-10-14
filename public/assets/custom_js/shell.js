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
    collapsed = read('mm-shell-collapsed') === '1' || read('mm-nav-mode') === 'mm-nav-collapsed';
    // Let Ace own nested dropdowns, but not shell visibility or rail dimensions.
    sidebar.classList.remove('menu-min', 'display', 'responsive-min');
    if (window.jQuery && window.jQuery.fn && window.jQuery.fn.ace_sidebar) {
        if (window.jQuery.fn.ace_sidebar.defaults) window.jQuery.fn.ace_sidebar.defaults.duration = 0;
        var aceInst = window.jQuery(sidebar).data('ace_sidebar');
        if (aceInst && aceInst.settings) aceInst.settings.duration = 0;
    }
    function syncNavModeButtons() {
        var curMode = body.classList.contains('mm-nav-collapsed') ? 'mm-nav-collapsed' : (body.classList.contains('mm-nav-rail') ? 'mm-nav-rail' : 'mm-nav-full');
        document.querySelectorAll('[data-mm-nav-toggle]').forEach(function (b) {
            if (b.dataset.mmNavMode) b.setAttribute('aria-pressed', String(b.dataset.mmNavMode === curMode));
            else b.setAttribute('aria-pressed', String(curMode !== 'mm-nav-full'));
        });
    }
    function render() {
        body.classList.toggle('mm-nav-collapsed', !mobile.matches && collapsed);
        if (!mobile.matches && !collapsed && !body.classList.contains('mm-nav-rail')) {
            body.classList.add('mm-nav-full');
        }
        body.classList.toggle('mm-nav-open', mobile.matches && isOpen);
        var visible = mobile.matches ? isOpen : !collapsed;
        toggle.setAttribute('aria-expanded', String(visible));
        sidebar.setAttribute('aria-hidden', String(!visible));
        sidebar.inert = !visible;
        if (backdrop) backdrop.hidden = !(mobile.matches && isOpen);
        if (main) main.inert = mobile.matches && isOpen;
        syncNavModeButtons();
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
            var isCurrentlyCollapsedOrRail = body.classList.contains('mm-nav-collapsed') || body.classList.contains('mm-nav-rail');
            collapsed = !isCurrentlyCollapsedOrRail;
            body.classList.remove('mm-nav-rail');
            body.classList.toggle('mm-nav-full', !collapsed);
            save('mm-shell-collapsed', collapsed ? '1' : '0');
            save('mm-nav-mode', collapsed ? 'mm-nav-collapsed' : 'mm-nav-full');
            render();
            // Existing Chosen adapters listen for this Ace compatibility event.
            if (window.jQuery) window.jQuery(document).trigger('settings.ace', ['sidebar_collapsed', collapsed]);
            window.dispatchEvent(new Event('resize'));
        }
    });
    // If user clicks a top-level dropdown-toggle while in 68px rail mode, auto-expand to full sidebar.
    menu.addEventListener('click', function (event) {
        var link = event.target && event.target.closest ? event.target.closest('a.dropdown-toggle') : null;
        if (!link || !body.classList.contains('mm-nav-rail')) return;
        collapsed = false;
        body.classList.remove('mm-nav-rail', 'mm-nav-collapsed');
        body.classList.add('mm-nav-full');
        save('mm-shell-collapsed', '0');
        save('mm-nav-mode', 'mm-nav-full');
        render();
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
    // Chromium may blur the sidebar link as soon as the layout hides it, before this listener runs; remember where focus last was.
    var lastFocus = null;
    document.addEventListener('focusin', function (event) { lastFocus = event.target; });
    var breakpointChanged = function () {
        var focusWasInSidebar = sidebar.contains(document.activeElement) || (document.activeElement === document.body && !!lastFocus && sidebar.contains(lastFocus));
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
    // Progressive Blade top sub-menu tab bar, role switcher, favorites bar & keyboard shortcuts when not server-rendered.
    if (location.hostname !== 'mm-shell.test' && !document.getElementById('mm-top-subnav')) {
        var pageContent = document.querySelector('.page-content');
        var topItems = Array.from(menu.children).filter(function (el) { return el.tagName === 'LI' && !el.classList.contains('mm-nav-section'); });
        // 1. Auto-mount Department Role View Switcher (#mm-role-switcher) in sidebar
        var searchBox = sidebar.querySelector('.mm-sidebar-search');
        if (searchBox && !document.getElementById('mm-role-switcher') && topItems.length > 1) {
            topItems.forEach(function (li) {
                if (li.getAttribute('data-role-tags')) return;
                var a = directLink(li);
                var txt = (a ? a.textContent : '').toLowerCase();
                var tags = ['all'];
                if (/dashboard|hotel|room|booking|guest|housekeeping|service|concierge|front/.test(txt)) tags.push('frontdesk');
                if (/dashboard|restaurant|bar|banquet|store|inventory|kitchen|pos|requisition/.test(txt)) tags.push('pos');
                if (/dashboard|account|voucher|ledger|report|permission|access|setting|website|audit|company/.test(txt)) tags.push('finance');
                if (tags.length === 1) tags.push('frontdesk', 'Finance');
                li.setAttribute('data-role-tags', tags.join(' '));
            });
            var roleBar = document.createElement('div');
            roleBar.className = 'mm-role-switcher';
            roleBar.id = 'mm-role-switcher';
            roleBar.setAttribute('role', 'group');
            roleBar.setAttribute('aria-label', 'Filter modules by staff department role');
            var roles = [
                ['all', 'All (' + topItems.length + ')'],
                ['frontdesk', 'Front Desk'],
                ['pos', 'F&B & Store'],
                ['finance', 'Finance & Admin']
            ];
            var savedRole = read('mm-role-view') || 'all';
            function applyRoleFilter(role) {
                save('mm-role-view', role);
                Array.from(roleBar.querySelectorAll('.mm-role-pill')).forEach(function (btn) {
                    var act = btn.getAttribute('data-role-view') === role;
                    btn.classList.toggle('is-active', act);
                    btn.setAttribute('aria-pressed', String(act));
                });
                topItems.forEach(function (li) {
                    var t = (li.getAttribute('data-role-tags') || 'all').split(/\s+/);
                    li.style.display = (role === 'all' || t.indexOf(role) >= 0) ? '' : 'none';
                });
            }
            roles.forEach(function (pair) {
                var btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'mm-role-pill' + (pair[0] === savedRole ? ' is-active' : '');
                btn.setAttribute('data-role-view', pair[0]);
                btn.setAttribute('data-role-filter', pair[0]);
                btn.setAttribute('aria-pressed', String(pair[0] === savedRole));
                btn.textContent = pair[1];
                btn.addEventListener('click', function () { applyRoleFilter(pair[0]); });
                roleBar.appendChild(btn);
            });
            searchBox.parentNode.insertBefore(roleBar, searchBox);
            applyRoleFilter(savedRole);
        }
        var activeModLi = topItems.find(function (li) { return li.classList.contains('active') || li.classList.contains('mm-mod-current') || li.querySelector('[aria-current="page"], li.active'); }) || topItems[0];
        if (pageContent && activeModLi) {
            var modAnchor = directLink(activeModLi);
            var modLabelEl = modAnchor && modAnchor.querySelector('.menu-text');
            var modLabel = (modLabelEl ? modLabelEl.textContent : (modAnchor ? modAnchor.textContent : 'Module')).trim();
            var subLinks = Array.from(activeModLi.querySelectorAll('.submenu a[href]')).filter(function (a) {
                var h = a.getAttribute('href');
                return h && h !== '#' && !/^javascript:/i.test(h);
            });
            if (!subLinks.length && modAnchor && modAnchor.getAttribute('href') && modAnchor.getAttribute('href') !== '#') {
                subLinks = [modAnchor];
            }
            if (subLinks.length) {
                var nav = document.createElement('nav');
                nav.className = 'mm-top-subnav is-sticky is-slid-up mm-no-print';
                nav.id = 'mm-top-subnav';
                nav.setAttribute('aria-label', modLabel + ' sub-menu tabs');
                var curtain = document.createElement('div');
                curtain.className = 'mm-top-subnav-curtain';
                curtain.id = 'mm-top-subnav-curtain';
                var head = document.createElement('div');
                head.className = 'mm-top-subnav-head';
                var titleWrap = document.createElement('div');
                titleWrap.className = 'mm-top-subnav-title';
                var titleSpan = document.createElement('span');
                titleSpan.textContent = modLabel;
                titleWrap.appendChild(titleSpan);
                var actIndex = subLinks.findIndex(function (a) { return a.getAttribute('aria-current') === 'page' || (a.parentElement && a.parentElement.classList.contains('active')); });
                if (actIndex < 0) actIndex = 0;
                var countBadge = document.createElement('span');
                countBadge.className = 'mm-badge mm-badge-paid';
                countBadge.style.fontSize = '11px';
                countBadge.textContent = (actIndex + 1) + ' / ' + subLinks.length + ' Tabs';
                titleWrap.appendChild(countBadge);
                head.appendChild(titleWrap);
                var rightWrap = document.createElement('div');
                rightWrap.style.cssText = 'display:flex;flex-wrap:wrap;align-items:center;gap:6px';
                var pinBtn = document.createElement('button');
                pinBtn.type = 'button';
                pinBtn.id = 'mm-top-pin-btn';
                pinBtn.className = 'mm-button mm-button-secondary';
                pinBtn.style.cssText = 'min-height:28px;padding:2px 10px;font-size:11.5px';
                pinBtn.title = 'Pin or unpin top sub-menu bar to sticky top while scrolling';
                var slideUpBtn = document.createElement('button');
                slideUpBtn.type = 'button';
                slideUpBtn.id = 'mm-top-slideup-btn';
                slideUpBtn.className = 'mm-button mm-button-secondary';
                slideUpBtn.style.cssText = 'min-height:28px;padding:2px 10px;font-size:11.5px';
                slideUpBtn.title = 'Slide sub-menu bar up under top navigation';
                slideUpBtn.innerHTML = '<i class="fa fa-chevron-up"></i> Slide Up';
                rightWrap.appendChild(pinBtn);
                rightWrap.appendChild(slideUpBtn);
                head.appendChild(rightWrap);
                var tabsWrap = document.createElement('div');
                tabsWrap.className = 'mm-top-subnav-tabs';
                tabsWrap.setAttribute('role', 'navigation');
                tabsWrap.setAttribute('aria-label', 'Module sub-pages');
                subLinks.forEach(function (a) {
                    var tab = document.createElement('a');
                    tab.href = a.href;
                    var isAct = a.getAttribute('aria-current') === 'page' || (a.parentElement && a.parentElement.classList.contains('active'));
                    tab.className = 'mm-top-tab' + (isAct ? ' is-active' : '');
                    if (isAct) tab.setAttribute('aria-current', 'page');
                    var lbl = document.createElement('span');
                    lbl.textContent = a.textContent.replace(/\s+/g, ' ').trim();
                    tab.appendChild(lbl);
                    tabsWrap.appendChild(tab);
                });
                curtain.appendChild(head);
                curtain.appendChild(tabsWrap);
                var rail = document.createElement('div');
                rail.className = 'mm-top-curtain-rail';
                var curtainBtn = document.createElement('button');
                curtainBtn.type = 'button';
                curtainBtn.id = 'mm-top-curtain-btn';
                curtainBtn.className = 'mm-top-curtain-btn';
                curtainBtn.setAttribute('aria-expanded', 'false');
                curtainBtn.setAttribute('aria-controls', 'mm-top-subnav-curtain');
                curtainBtn.title = 'Pull down or slide up sub-menu curtain under top navbar';
                var cIcon = document.createElement('i');
                cIcon.className = 'fa fa-chevron-down mm-top-curtain-arrow';
                cIcon.id = 'mm-top-curtain-icon';
                var cLabel = document.createElement('span');
                cLabel.id = 'mm-top-curtain-label';
                curtainBtn.appendChild(cIcon);
                curtainBtn.appendChild(cLabel);
                rail.appendChild(curtainBtn);
                nav.appendChild(curtain);
                nav.appendChild(rail);
                pageContent.insertBefore(nav, pageContent.firstChild);
                var pinned = read('mm-top-subnav-pinned') !== '0';
                function syncPin() {
                    nav.classList.toggle('is-sticky', pinned);
                    pinBtn.setAttribute('aria-pressed', String(pinned));
                    pinBtn.innerHTML = '<i class="fa fa-thumb-tack"></i> ' + (pinned ? 'Pinned' : 'Pin to Top');
                }
                syncPin();
                pinBtn.addEventListener('click', function () {
                    pinned = !pinned;
                    save('mm-top-subnav-pinned', pinned ? '1' : '0');
                    syncPin();
                });
                var isOpen = read('mm-top-subnav-open') === '1';
                function syncCurtain() {
                    nav.classList.toggle('is-slid-up', !isOpen);
                    curtainBtn.setAttribute('aria-expanded', String(isOpen));
                    cLabel.textContent = isOpen ? ('Slide Up · ' + modLabel) : (modLabel + ' (' + subLinks.length + ' Tabs)');
                }
                syncCurtain();
                function toggleCurtain(force) {
                    isOpen = typeof force === 'boolean' ? force : !isOpen;
                    save('mm-top-subnav-open', isOpen ? '1' : '0');
                    syncCurtain();
                }
                curtainBtn.addEventListener('click', function () { toggleCurtain(); });
                slideUpBtn.addEventListener('click', function () { toggleCurtain(false); });
                document.addEventListener('keydown', function (ev) {
                    var tag = (ev.target && ev.target.tagName || '').toLowerCase();
                    if (tag === 'input' || tag === 'textarea' || tag === 'select' || (ev.target && ev.target.isContentEditable)) return;
                    if (ev.key === '/' && !ev.altKey && !ev.ctrlKey && !ev.metaKey && filter) {
                        ev.preventDefault();
                        filter.focus();
                    }
                });
            }
        }
    }
    if (!window.__mmFullscreenBound) {
        window.__mmFullscreenBound = true;
        function setFocusedFullscreen(force) {
            var next = typeof force === 'boolean' ? force : !body.classList.contains('mm-focused-fullscreen');
            body.classList.toggle('mm-focused-fullscreen', next);
            var btns = document.querySelectorAll('[data-mm-fullscreen-toggle], #mm-focused-fullscreen-btn');
            for (var i = 0; i < btns.length; i++) {
                btns[i].classList.toggle('is-active', next);
                btns[i].setAttribute('aria-pressed', String(next));
                btns[i].innerHTML = next
                    ? '<i class="fa fa-compress" aria-hidden="true"></i> Exit Fullscreen'
                    : '<i class="fa fa-arrows-alt" aria-hidden="true"></i> Focused Fullscreen';
            }
        }
        window.__mmSetFocusedFullscreen = setFocusedFullscreen;
        document.addEventListener('click', function (ev) {
            var tBtn = ev.target && ev.target.closest && ev.target.closest('[data-mm-fullscreen-toggle], #mm-focused-fullscreen-btn');
            if (tBtn) {
                ev.preventDefault();
                setFocusedFullscreen();
                return;
            }
            var xBtn = ev.target && ev.target.closest && ev.target.closest('[data-mm-fullscreen-exit], #mm-fullscreen-exit-btn');
            if (xBtn) {
                ev.preventDefault();
                setFocusedFullscreen(false);
            }
        });
        document.addEventListener('keydown', function (ev) {
            if (ev.key === 'Escape' && body.classList.contains('mm-focused-fullscreen')) {
                setFocusedFullscreen(false);
                return;
            }
            var tag = (ev.target && ev.target.tagName || '').toLowerCase();
            if (tag === 'input' || tag === 'textarea' || tag === 'select' || (ev.target && ev.target.isContentEditable)) return;
            if (ev.altKey && !ev.ctrlKey && !ev.metaKey && (ev.key || '').toLowerCase() === 'z') {
                ev.preventDefault();
                setFocusedFullscreen();
            }
        });
    }
    syncDisclosures();
    render();
}());
