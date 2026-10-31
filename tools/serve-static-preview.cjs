#!/usr/bin/env node
/**
 * Serve a `tools/export-static-preview.cjs` build the way GitHub Pages will.
 *
 * A project site lives under /<repo>/, so a static server rooted at the export
 * directory is not a faithful test: root-relative links would resolve against
 * the server root and quietly work. This mounts the export under a base path and
 * redirects "/" to it, which is exactly what Pages does.
 *
 * Usage: node tools/serve-static-preview.cjs [--dir dist-preview] [--base /mmheritagehotel] [--port 8099]
 */

const http = require('http');
const fs = require('fs');
const path = require('path');

const ROOT = path.resolve(__dirname, '..');
const arg = (name, fallback) => {
    const i = process.argv.indexOf(name);
    return i >= 0 ? process.argv[i + 1] : fallback;
};

const DIR = path.resolve(ROOT, arg('--dir', 'dist-preview'));
const BASE = ('/' + arg('--base', 'mmheritagehotel').replace(/^\/+|\/+$/g, ''));
const PORT = Number(arg('--port', 8099));

const types = {
    '.html': 'text/html; charset=utf-8', '.css': 'text/css', '.js': 'text/javascript',
    '.json': 'application/json', '.png': 'image/png', '.jpg': 'image/jpeg', '.jpeg': 'image/jpeg',
    '.gif': 'image/gif', '.svg': 'image/svg+xml', '.webp': 'image/webp', '.ico': 'image/x-icon',
    '.woff': 'font/woff', '.woff2': 'font/woff2', '.ttf': 'font/ttf', '.eot': 'application/vnd.ms-fontobject',
    '.csv': 'text/csv', '.pdf': 'application/pdf', '.map': 'application/json',
};

if (!fs.existsSync(DIR)) {
    console.error('No export at ' + DIR + ' - run tools/export-static-preview.cjs first.');
    process.exit(1);
}

function sendFile(res, file) {
    fs.readFile(file, (err, data) => {
        if (err) {
            // Pages would serve 404.html; the export does not ship one, so fall
            // back to the explorer rather than a bare 404.
            const fallback = path.join(DIR, 'preview', 'index.html');
            if (fs.existsSync(fallback)) {
                res.writeHead(200, { 'Content-Type': types['.html'] });
                return res.end(fs.readFileSync(fallback));
            }
            res.writeHead(404, { 'Content-Type': 'text/plain' });
            return res.end('Not found: ' + file);
        }
        res.writeHead(200, { 'Content-Type': types[path.extname(file)] || 'application/octet-stream' });
        res.end(data);
    });
}

http.createServer((req, res) => {
    const pathname = decodeURIComponent(new URL(req.url, 'http://preview').pathname);

    if (pathname === '/' || pathname === '') {
        res.writeHead(302, { Location: BASE + '/' });
        return res.end();
    }
    if (pathname !== BASE && !pathname.startsWith(BASE + '/')) {
        res.writeHead(404, { 'Content-Type': 'text/plain' });
        return res.end('Outside the Pages base path: ' + pathname);
    }

    const relative = pathname.slice(BASE.length) || '/';
    const target = path.join(DIR, relative);
    if (!target.startsWith(DIR)) {
        res.writeHead(403);
        return res.end();
    }

    if (relative.endsWith('/')) return sendFile(res, path.join(target, 'index.html'));
    if (path.extname(target)) return sendFile(res, target);

    // Project pages redirects an extensionless path to the slash form.
    res.writeHead(301, { Location: BASE + relative + '/' });
    res.end();
}).listen(PORT, '0.0.0.0', () => {
    console.log('static preview: http://localhost:' + PORT + BASE + '/');
});
