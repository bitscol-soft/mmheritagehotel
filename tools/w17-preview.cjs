// W1.7 standalone preview server.
//
// The full preview-server.cjs only serves pre-rendered fixtures and does
// not reflect W1.7 changes. This server serves one hand-rendered HTML
// file (tools/fixtures/w17-preview.html) that mirrors the slot structure,
// classes, and form hooks the W1.7 Blade templates emit, so the user can
// see what the toolbar + data-table pattern looks like in context.
//
// Bound to 0.0.0.0 so the Arena preview proxy can reach it.
const http = require('http');
const fs = require('fs');
const path = require('path');

const root = path.resolve(__dirname, '..');
const types = {
    '.html': 'text/html; charset=utf-8',
    '.css': 'text/css',
    '.js': 'text/javascript',
    '.png': 'image/png',
    '.svg': 'image/svg+xml',
    '.woff2': 'font/woff2',
    '.woff': 'font/woff',
    '.ttf': 'font/ttf',
    '.eot': 'application/vnd.ms-fontobject',
    '.jpg': 'image/jpeg',
    '.jpeg': 'image/jpeg',
    '.gif': 'image/gif',
};

http.createServer((req, res) => {
    const url = new URL(req.url, 'http://w17');

    // /  ->  the W1.7 preview HTML
    if (url.pathname === '/' || url.pathname === '/index.html') {
        try {
            const html = fs.readFileSync(path.join(root, 'tools/fixtures/w17-preview.html'), 'utf8');
            res.writeHead(200, { 'Content-Type': types['.html'], 'Cache-Control': 'no-store' });
            return res.end(html);
        } catch (e) {
            res.writeHead(500);
            return res.end(String(e));
        }
    }

    // /assets/...  ->  public/assets/...
    if (url.pathname.startsWith('/assets/')) {
        const file = path.resolve(root, 'public', '.' + decodeURIComponent(url.pathname));
        if (!file.startsWith(root + '/public/assets/')) {
            res.writeHead(403);
            return res.end();
        }
        return fs.readFile(file, (err, data) => {
            if (err) {
                res.writeHead(404);
                return res.end('Not found: ' + url.pathname);
            }
            res.writeHead(200, {
                'Content-Type': types[path.extname(file)] || 'application/octet-stream',
                'Cache-Control': 'no-store',
            });
            res.end(data);
        });
    }

    res.writeHead(404, { 'Content-Type': 'text/plain' });
    res.end('Not found: ' + url.pathname);
}).listen(3001, '0.0.0.0');

console.log('W1.7 preview server: http://0.0.0.0:3001/');
