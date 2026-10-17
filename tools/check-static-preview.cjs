#!/usr/bin/env node
/**
 * Verify a static preview export: every internal link and asset must resolve to
 * a real file, and no page may keep a root-absolute URL (which would escape a
 * GitHub Pages project sub-path).
 *
 * Usage: node tools/check-static-preview.cjs [dist-preview]
 */

const fs = require('fs');
const path = require('path');

const ROOT = path.resolve(__dirname, '..');
const DIR = path.resolve(ROOT, process.argv[2] || 'dist-preview');

// Vendored HTML inside the copied asset trees (jQuery UI dialog samples, fonts
// previews) is not a screen: it is shipped as-is by public/ and the live
// preview server never rewrites it either.
const ASSET_TREES = new Set(['assets', 'frontend', 'food_menu']);

function walk(dir, out = []) {
    for (const entry of fs.readdirSync(dir, { withFileTypes: true })) {
        const full = path.join(dir, entry.name);
        if (entry.isDirectory()) {
            if (path.dirname(full) === DIR && ASSET_TREES.has(entry.name)) continue;
            walk(full, out);
        } else if (entry.name.endsWith('.html')) out.push(full);
    }
    return out;
}

function exists(target) {
    if (fs.existsSync(target) && fs.statSync(target).isFile()) return true;
    if (fs.existsSync(path.join(target, 'index.html'))) return true;
    return false;
}

const pages = walk(DIR);
const broken = [];
const absolute = [];
const external = [];
let links = 0;

for (const page of pages) {
    const html = fs.readFileSync(page, 'utf8');
    const rel = path.relative(DIR, page);
    for (const match of html.matchAll(/\b(?:href|src|poster)\s*=\s*["']([^"']*)["']/gi)) {
        const value = match[1].trim();
        if (!value || value.startsWith('#') || value.startsWith('data:') || value.startsWith('javascript:')) continue;
        if (/^(https?:)?\/\//i.test(value)) { external.push(value); continue; }
        if (/^(mailto|tel):/i.test(value)) continue;
        if (value.startsWith('/')) { absolute.push(rel + ' -> ' + value); continue; }

        links++;
        const cut = value.search(/[?#]/);
        const clean = decodeURIComponent(cut >= 0 ? value.slice(0, cut) : value);
        if (!clean) continue;
        const target = path.resolve(path.dirname(page), clean);
        if (!target.startsWith(DIR)) { broken.push(rel + ' -> escapes export: ' + value); continue; }
        if (!exists(target)) broken.push(rel + ' -> ' + value);
    }
}

// CSS and JS files are loaded per page; a missing one shows up as a link above,
// but url(...) inside a stylesheet would not.
const cssMissing = [];
function walkCss(dir) {
    for (const entry of fs.readdirSync(dir, { withFileTypes: true })) {
        const full = path.join(dir, entry.name);
        if (entry.isDirectory()) walkCss(full);
        else if (entry.name.endsWith('.css')) {
            const css = fs.readFileSync(full, 'utf8');
            for (const m of css.matchAll(/url\(\s*['"]?([^'")]+)['"]?\s*\)/gi)) {
                const v = m[1].trim();
                if (!v || v.startsWith('data:') || /^(https?:)?\/\//i.test(v)) continue;
                if (v.startsWith('/')) { absolute.push(path.relative(DIR, full) + ' (css) -> ' + v); continue; }
                const target = path.resolve(path.dirname(full), v.split('?')[0].split('#')[0]);
                if (!fs.existsSync(target)) cssMissing.push(path.relative(DIR, full) + ' -> ' + v);
            }
        }
    }
}
if (fs.existsSync(path.join(DIR, 'assets'))) walkCss(DIR);

const uniq = (arr) => [...new Set(arr)];
const summary = {
    pages: pages.length,
    internal_links_checked: links,
    broken_links: uniq(broken).length,
    root_absolute_refs: uniq(absolute).length,
    // Pre-existing gaps in the vendored stylesheets under public/assets - the
    // live preview server 404s them too, so they are reported, not enforced.
    missing_css_urls: uniq(cssMissing).length,
    external_refs: uniq(external).length,
};

console.log(JSON.stringify(summary, null, 2));
for (const b of uniq(broken).slice(0, 20)) console.log('BROKEN   ' + b);
for (const a of uniq(absolute).slice(0, 20)) console.log('ABSOLUTE ' + a);
for (const c of uniq(cssMissing).slice(0, 10)) console.log('CSS-URL  ' + c);

const ok = summary.broken_links === 0 && summary.root_absolute_refs === 0;
console.log(ok ? 'STATIC_PREVIEW_OK' : 'STATIC_PREVIEW_FAILED');
process.exit(ok ? 0 : 1);
