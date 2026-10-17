#!/usr/bin/env node
/**
 * Static export of the visual preview, for GitHub Pages.
 *
 * `tools/preview-server.cjs` renders every renovated screen on the fly from
 * `tools/fixtures/*`, which is perfect for a sandbox but invisible to anyone
 * without the box. This script starts that server, crawls the same routes over
 * HTTP (so the output is byte-identical to what the server verified), and writes
 * a directory that a plain static host can serve.
 *
 * Two things differ from the live server and are handled here:
 *
 *  1. GitHub Pages serves a project site under /<repo>/, so root-absolute URLs
 *     (`/assets/...`, `/preview/...`) would escape the site. Every page is
 *     written at <route>/index.html and its root-absolute URLs are rewritten to
 *     depth-relative ones.
 *  2. There is no PHP behind the pages, so form posts are neutralised: every
 *     <form> action becomes "#" and a small script turns submit into a notice.
 *
 * Usage: node tools/export-static-preview.cjs [--out dist-preview] [--port 4179]
 */

const fs = require('fs');
const path = require('path');
const http = require('http');
const { spawn } = require('child_process');

const ROOT = path.resolve(__dirname, '..');
const OUT = path.resolve(ROOT, argValue('--out') || 'dist-preview');
const PORT = Number(argValue('--port') || 4179);
const MAX_PAGES = Number(argValue('--max') || 900);

function argValue(name) {
    const i = process.argv.indexOf(name);
    return i >= 0 ? process.argv[i + 1] : null;
}

/**
 * Routes the preview server resolves without being listed in the explorer:
 * the aliases the real application uses. Crawling the menus discovers most of
 * them too, but seeding them means a screen is exported even if no menu links
 * to it.
 */
const ALIASES = [
    '/', '/preview', '/dashboard', '/home',
    '/login', '/logout', '/password', '/notifications', '/system-settings',
    '/ui-kit', '/wiki', '/help', '/sop-print', '/onboarding', '/tape-chart',
    '/rooms', '/hotel/rooms', '/website/rooms',
    '/guests', '/hotel/guests',
    '/bookings', '/hotel/booking', '/hotel/bookings',
    '/hotel/house-keeping',
    '/report/expected-arrival', '/hotel/reports/expected-arrival',
    '/report/expected-departure', '/hotel/reports/expected-departure',
    '/report/in-house-guest', '/hotel/reports/in-house-guest',
    '/hotel/booking/booking-next', '/hotel/booking/create',
    '/restaurant', '/restaurant/sales-v2/create', '/rst/sales-v2/create', '/pos',
    '/bar', '/bar/sales-v2/create', '/bar-pos',
    '/restaurant/sales', '/restaurant/sales/create', '/rst/sales/create',
    '/restaurant/products',
    '/bar/sales', '/bar/sales/create', '/bar/stock',
    '/banquet/reservations', '/banquet/packages',
    '/services', '/services/charges',
    '/store/purchases', '/store/requisitions',
    '/finance/ledger', '/finance/payments',
    '/website/pages',
    '/groups', '/companies', '/permissions', '/permissions/users',
    '/hotel/booking-purpose',
];

const SUBMIT_GUARD =
    '<script>(function(){document.addEventListener("submit",function(e){' +
    'e.preventDefault();e.stopPropagation();' +
    'alert("Static preview: nothing is saved here.");},true);})();</script>';

function get(route) {
    return new Promise((resolve, reject) => {
        const req = http.get(
            { host: '127.0.0.1', port: PORT, path: route, headers: { accept: 'text/html' } },
            (res) => {
                const chunks = [];
                res.on('data', (c) => chunks.push(c));
                res.on('end', () => resolve({
                    status: res.statusCode,
                    body: Buffer.concat(chunks).toString('utf8'),
                }));
            }
        );
        req.on('error', reject);
        req.setTimeout(60000, () => req.destroy(new Error('timeout: ' + route)));
    });
}

function waitForServer(attempts = 100) {
    return new Promise((resolve, reject) => {
        let tries = 0;
        const tick = () => {
            get('/preview').then(() => resolve()).catch(() => {
                if (++tries >= attempts) return reject(new Error('preview server did not start'));
                setTimeout(tick, 100);
            });
        };
        tick();
    });
}

/** Is this a screen (a page we should export), as opposed to an asset or anchor? */
function isPageLike(url) {
    if (!url || !url.startsWith('/') || url.startsWith('//')) return false;
    const clean = url.split('#')[0].split('?')[0];
    if (!clean) return false;
    if (/^\/(assets|frontend|food_menu)\//.test(clean)) return false;
    const last = clean.split('/').filter(Boolean).pop() || '';
    if (/\.[a-z0-9]{1,8}$/i.test(last)) return false; // a file, not a screen
    return true;
}

function linksIn(html) {
    const found = new Set();
    const re = /\b(?:href|action)\s*=\s*["']([^"']+)["']/gi;
    let m;
    while ((m = re.exec(html))) {
        const value = m[1].trim();
        if (isPageLike(value)) found.add(value.split('#')[0].split('?')[0]);
    }
    return found;
}

function outputPath(route) {
    const clean = route.split('#')[0].split('?')[0];
    if (clean === '/' || clean === '') return path.join(OUT, 'index.html');
    const segments = clean.split('/').filter(Boolean).map((s) => decodeURIComponent(s));
    return path.join(OUT, ...segments, 'index.html');
}

const ASSET_ROOTS = ['assets', 'frontend', 'food_menu'];

/**
 * Rewrite one page for static hosting. `depth` is how many directories deep the
 * page sits, i.e. the number of `../` needed to get back to the site root.
 */
function rewrite(html, depth) {
    const prefix = depth > 0 ? '../'.repeat(depth) : '';

    // Forms cannot post to a static host. Point them at "#" - the injected
    // guard intercepts submit - and keep the markup otherwise untouched.
    let out = html.replace(/\baction\s*=\s*"[^"]*"/gi, 'action="#"');

    // Root-absolute attributes -> depth-relative. `//host` is protocol-relative
    // and deliberately excluded.
    out = out.replace(/(\b(?:href|src|poster)\s*=\s*["'])\/(?!\/)/gi, (match, attr) => attr + prefix);
    out = out.replace(/\bhref\s*=\s*["']\/["']/gi, 'href="' + (prefix || './') + '"');

    // The same paths appear as JS string literals and inline CSS url()s.
    out = out.replace(/(["'(`])\/(preview|assets|frontend|food_menu)(?=[/"'`)\s?#])/g,
        (match, quote, segment) => quote + prefix + segment);

    // Directory indexes: an extensionless internal link needs the trailing
    // slash or every click pays a redirect hop.
    out = out.replace(/\bhref\s*=\s*"([^"]*)"/gi, (match, value) => {
        if (!value || value.startsWith('#') || value.startsWith('//')) return match;
        if (/^[a-z][a-z0-9+.-]*:/i.test(value)) return match;
        if (/^https?:/i.test(value)) return match;
        const cut = value.search(/[?#]/);
        const pathPart = cut >= 0 ? value.slice(0, cut) : value;
        const rest = cut >= 0 ? value.slice(cut) : '';
        if (!pathPart || pathPart.endsWith('/') || pathPart === '.' || pathPart === '..') return match;
        const last = pathPart.split('/').filter(Boolean).pop() || '';
        if (/\.[a-z0-9]{1,8}$/i.test(last)) return match; // a file
        return 'href="' + pathPart + '/' + rest + '"';
    });

    return out.replace(/<\/body>/i, SUBMIT_GUARD + '</body>');
}

function copyDir(from, to) {
    fs.mkdirSync(to, { recursive: true });
    for (const entry of fs.readdirSync(from, { withFileTypes: true })) {
        const src = path.join(from, entry.name);
        const dst = path.join(to, entry.name);
        if (entry.isDirectory()) copyDir(src, dst);
        else if (entry.isFile()) fs.copyFileSync(src, dst);
    }
}


/** Existing file in the export to stand in for a missing one of the same kind. */
function findStandIn(kind) {
    const candidates = {
        room: ['assets/uploads/hotel/room', ['jpg', 'jpeg', 'png']],
        image: ['assets/images/avatars', ['jpg', 'jpeg', 'png']],
    }[kind];
    if (!candidates) return null;
    const [dir, exts] = candidates;
    const abs = path.join(OUT, dir);
    if (!fs.existsSync(abs)) return null;
    const hit = fs.readdirSync(abs).find((f) => exts.includes(path.extname(f).slice(1).toLowerCase()));
    return hit ? path.join(abs, hit) : null;
}

/**
 * Walk the exported pages and satisfy any link that points inside the export
 * but has no file behind it. Fixtures reference a few uploads that are not in
 * the repo; those stay broken in every environment, so filling them here keeps
 * the published preview honest about layout without pretending data exists.
 */
function fillDanglingReferences() {
    const filled = [];
    const seen = new Set();

    const scan = (dir) => {
        for (const entry of fs.readdirSync(dir, { withFileTypes: true })) {
            const full = path.join(dir, entry.name);
            if (entry.isDirectory()) {
                if (path.dirname(full) === OUT && ASSET_ROOTS.includes(entry.name)) continue;
                scan(full);
                continue;
            }
            if (!entry.name.endsWith('.html')) continue;

            const html = fs.readFileSync(full, 'utf8');
            for (const m of html.matchAll(/\b(?:href|src)\s*=\s*["']([^"']+)["']/gi)) {
                const value = m[1].trim();
                if (!value || value.startsWith('#') || value.startsWith('//')) continue;
                if (/^[a-z][a-z0-9+.-]*:/i.test(value)) continue;

                const cut = value.search(/[?#]/);
                const clean = decodeURIComponent(cut >= 0 ? value.slice(0, cut) : value);
                if (!clean) continue;

                const target = path.resolve(path.dirname(full), clean);
                if (!target.startsWith(OUT)) continue;
                if (fs.existsSync(target) || fs.existsSync(path.join(target, 'index.html'))) continue;
                if (seen.has(target)) continue;
                seen.add(target);

                const ext = path.extname(target).toLowerCase();
                if (!ext) continue;

                fs.mkdirSync(path.dirname(target), { recursive: true });
                const rel = path.relative(OUT, target);

                if (ext === '.csv') {
                    fs.writeFileSync(target,
                        'name,batch_number,category,generic,medicine_type,supplier,pack_size,small_unit,big_unit\n');
                    filled.push(rel + ' (sample CSV header)');
                    continue;
                }
                if (!['.jpg', '.jpeg', '.png', '.gif', '.svg'].includes(ext)) continue;

                const standIn = /uploads\/hotel\/room/.test(rel) ? findStandIn('room') : findStandIn('image');
                if (standIn) {
                    fs.copyFileSync(standIn, target);
                    filled.push(rel + ' <- ' + path.relative(OUT, standIn));
                }
            }
        }
    };

    scan(OUT);
    return filled;
}

async function main() {
    const server = spawn(process.execPath, [path.join(ROOT, 'tools/preview-server.cjs')], {
        env: { ...process.env, PORT: String(PORT) },
        stdio: ['ignore', 'pipe', 'pipe'],
    });
    let serverErr = '';
    server.stderr.on('data', (d) => { serverErr += d.toString(); });
    server.on('exit', (code) => {
        if (code) console.error('preview server exited with ' + code + '\n' + serverErr);
    });

    try {
        await waitForServer();
        console.log('preview server up on :' + PORT);

        fs.rmSync(OUT, { recursive: true, force: true });
        fs.mkdirSync(OUT, { recursive: true });

        const queue = [...ALIASES];
        const seen = new Set();
        const written = [];
        const failed = [];

        while (queue.length && seen.size < MAX_PAGES) {
            const route = queue.shift();
            if (seen.has(route)) continue;
            seen.add(route);

            let page;
            try {
                page = await get(route);
            } catch (e) {
                failed.push(route + ' (request failed: ' + e.message + ')');
                continue;
            }
            if (page.status !== 200) {
                failed.push(route + ' (HTTP ' + page.status + ')');
                continue;
            }

            // Links must be read from the original response: by the time the
            // rewrite has run they are relative to the page, not to the site.
            const discovered = linksIn(page.body);

            const depth = route === '/' ? 0 : route.split('/').filter(Boolean).length;
            const html = rewrite(page.body, depth);
            const file = outputPath(route);
            fs.mkdirSync(path.dirname(file), { recursive: true });
            fs.writeFileSync(file, html);
            written.push({ route, file, bytes: Buffer.byteLength(html) });

            for (const link of discovered) {
                if (!seen.has(link)) queue.push(link);
            }
        }

        for (const asset of ASSET_ROOTS) {
            const from = path.join(ROOT, 'public', asset);
            if (fs.existsSync(from)) copyDir(from, path.join(OUT, asset));
        }
        for (const file of ['favicon.ico', 'robots.txt']) {
            const from = path.join(ROOT, 'public', file);
            if (fs.existsSync(from)) fs.copyFileSync(from, path.join(OUT, file));
        }

        // A handful of fixtures point at uploads that were never committed (the
        // live preview 404s them too). Stand them in with real files from the
        // export so the published screens render intact, and say which ones.
        const filled = fillDanglingReferences();
        if (filled.length) {
            console.log('filled ' + filled.length + ' dangling fixture reference(s):');
            filled.forEach((f) => console.log('  ' + f));
        }

        // GitHub Pages runs Jekyll only for branch builds, but the marker costs
        // nothing and keeps the artifact portable to any static host.
        fs.writeFileSync(path.join(OUT, '.nojekyll'), '');

        const totalBytes = written.reduce((n, w) => n + w.bytes, 0);
        fs.writeFileSync(path.join(OUT, 'export-manifest.json'), JSON.stringify({
            generated_at: new Date().toISOString(),
            pages: written.length,
            html_bytes: totalBytes,
            routes: written.map((w) => w.route).sort(),
        }, null, 2));

        console.log('exported ' + written.length + ' screens (' +
            (totalBytes / 1024 / 1024).toFixed(1) + ' MB of HTML) to ' + path.relative(ROOT, OUT));
        if (failed.length) {
            console.log('skipped ' + failed.length + ' route(s):');
            failed.slice(0, 20).forEach((f) => console.log('  ' + f));
        }
    } finally {
        server.kill('SIGTERM');
    }
}

main().catch((e) => {
    console.error(e);
    process.exit(1);
});
