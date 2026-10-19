#!/usr/bin/env node
/**
 * Laravel 8 -> 9 -> 10 -> 11 step runner (BUGS #31).
 *
 * Rewrites composer.json and the version-coupled app code for ONE step, so the
 * mechanical part of docs/LARAVEL-11-UPGRADE.md stops being a manual checklist.
 * It deliberately does NOT run composer: the lock file and the committed vendor
 * tree are the deploy artifact, and those must be refreshed on a machine with
 * Packagist access (this repository ships vendor/ in git).
 *
 * Dry run by default. Nothing is written until you pass --apply.
 *
 *   node tools/laravel11-bump.cjs --step 1                 # show the diff
 *   node tools/laravel11-bump.cjs --step 1 --apply         # write it
 *   composer update && composer install --no-dev --optimize-autoloader
 *   git add composer.json composer.lock vendor/ && git commit
 *
 * Options:
 *   --step N    1, 2 or 3 (required)
 *   --apply     write the changes (default: preview only)
 *   --force     skip the "working tree is dirty" safety check
 */

const fs = require('fs');
const path = require('path');
const { execFileSync } = require('child_process');

const ROOT = path.resolve(__dirname, '..');
const argv = process.argv.slice(2);
const flag = (name) => argv.includes(name);
const value = (name) => {
    const i = argv.indexOf(name);
    return i >= 0 ? argv[i + 1] : null;
};

const STEP = Number(value('--step'));
const APPLY = flag('--apply');
const FORCE = flag('--force');

if (![1, 2, 3].includes(STEP)) {
    console.error('usage: node tools/laravel11-bump.cjs --step 1|2|3 [--apply] [--force]');
    process.exit(2);
}

/**
 * `set` entries are written into require/require-dev; `del` entries are removed;
 * a `set` whose package is absent is skipped (the runbook listed laravel/sail,
 * which this project never required).
 */
const STEPS = {
    1: {
        label: 'Laravel 8 -> 9 (PHP >= 8.0.2)',
        require: {
            php: '^8.0.2',
            'laravel/framework': '^9.0',
            'laravel/ui': '^4.0',
            'laravel/sanctum': '^2.14',
        },
        requireDev: {
            'nunomaduro/collision': '^6.1',
            'phpunit/phpunit': '^9.5',
        },
        // Packages introduced by the step (absent today).
        addDev: {
            'spatie/laravel-ignition': '^1.0',
        },
        del: ['fruitcake/laravel-cors', 'facade/ignition'],
        code: [renameRouteMiddleware, swapCorsMiddleware],
        after: [
            'config/cors.php is read by the framework from L9 on - keep it.',
            'If anything still imports Fruitcake\\Cors, replace it with Illuminate\\Http\\Middleware\\HandleCors.',
        ],
    },
    2: {
        label: 'Laravel 9 -> 10 (PHP >= 8.1)',
        require: {
            'laravel/framework': '^10.0',
            'laravel/sanctum': '^3.2',
            'laravel/ui': '^4.2',
        },
        requireDev: {
            'nunomaduro/collision': '^7.0',
            'phpunit/phpunit': '^10.0',
            'spatie/laravel-ignition': '^2.0',
        },
        del: [],
        code: [migratePhpunitXml],
        after: ['Audit rajibbinalam/laravel-slug-generator and skycoder/laravel-filesaver for L10 support.'],
    },
    3: {
        label: 'Laravel 10 -> 11 (PHP >= 8.2)',
        require: {
            php: '^8.2',
            'laravel/framework': '^11.0',
            'laravel/sanctum': '^3.3',
            'laravel/tinker': '^2.9',
            'laravel/ui': '^4.5',
        },
        requireDev: {
            'nunomaduro/collision': '^8.1',
            'phpunit/phpunit': '^10.5|^11.0',
            'barryvdh/laravel-debugbar': '^3.10',
        },
        del: [],
        code: [],
        after: [
            'Carbon 3 ships with L11: re-check every ->diffIn*() call site (the guard lists them).',
            'phpunit 11 drops doc-comment metadata - this suite uses none, but keep it that way.',
        ],
    },
};

const plan = STEPS[STEP];

// ---------------------------------------------------------------- preflight
if (!FORCE) {
    try {
        const dirty = execFileSync('git', ['status', '--porcelain'], { cwd: ROOT, encoding: 'utf8' }).trim();
        if (dirty) {
            console.error('Working tree is dirty. Commit or stash first so the bump is a reviewable diff.\n');
            console.error(dirty.split('\n').slice(0, 10).join('\n'));
            console.error('\n(--force to proceed anyway)');
            process.exit(1);
        }
    } catch (e) {
        console.error('Could not read git status; pass --force to skip this check.');
        process.exit(1);
    }
}

const edits = [];   // {file, before, after, note}

function edit(content, file, note) {
    const before = content;
    const after = content;
    return { file, before, after, note };
}

// ------------------------------------------------------------ code changes
function renameRouteMiddleware(ctx) {
    const file = 'app/Http/Kernel.php';
    const src = ctx.read(file);
    if (!src.includes('$routeMiddleware')) return null;
    return {
        file,
        before: src,
        after: src.replace(/\$routeMiddleware\b/g, '$middlewareAliases'),
        note: 'Kernel::$routeMiddleware -> $middlewareAliases (L9 renamed it; L8 only reads the old name)',
    };
}

function swapCorsMiddleware(ctx) {
    const file = 'app/Http/Kernel.php';
    const src = ctx.read(file);
    if (!src.includes('Fruitcake\\Cors\\HandleCors')) return null;
    return {
        file,
        before: src,
        after: src.replace(/\\Fruitcake\\Cors\\HandleCors::class/g, '\\Illuminate\\Http\\Middleware\\HandleCors::class'),
        note: 'Fruitcake\\Cors\\HandleCors -> Illuminate\\Http\\Middleware\\HandleCors (CORS is in-framework from L9)',
    };
}

function migratePhpunitXml(ctx) {
    const file = 'phpunit.xml';
    const src = ctx.read(file);
    if (!src.includes('processUncoveredFiles') && src.includes('<source>')) return null;

    let out = src
        // PHPUnit 10 moved this to <source><include>; the attribute no longer exists.
        .replace(/<coverage\s+processUncoveredFiles="[^"]*"\s*>/g, '<source>')
        .replace(/<\/coverage>/g, '</source>');

    // Collapse the duplicated <server> entries the old file carried.
    const seen = new Set();
    out = out.split('\n').filter((line) => {
        const m = line.match(/<server\s+name="([^"]+)"/);
        if (!m) return true;
        if (seen.has(m[1])) return false;
        seen.add(m[1]);
        return true;
    }).join('\n');

    return {
        file,
        before: src,
        after: out,
        note: 'phpunit.xml -> PHPUnit 10 schema (<coverage processUncoveredFiles> became <source>); duplicate <server> entries dropped',
    };
}

// Several steps touch the same file (Kernel.php needs both the alias rename and
// the CORS swap). Each transform must therefore read what the previous one
// produced, and the file must be written once.
const originals = new Map();
const pendingFiles = new Map();

const ctx = {
    read: (f) => pendingFiles.has(f) ? pendingFiles.get(f) : fs.readFileSync(path.join(ROOT, f), 'utf8'),
};

for (const fn of plan.code) {
    const result = fn(ctx);
    if (!result) continue;
    if (!originals.has(result.file)) originals.set(result.file, result.before);
    pendingFiles.set(result.file, result.after);
    const existing = edits.find((e) => e.file === result.file);
    if (existing) existing.note += '; ' + result.note;
    else edits.push({ file: result.file, before: result.before, after: result.after, note: result.note });
}

// Refresh the `after` of every code edit from the final pending content.
for (const e of edits) {
    if (pendingFiles.has(e.file)) e.after = pendingFiles.get(e.file);
}

// --------------------------------------------------------- composer.json
//
// Edited as text, not re-serialised: stringifying the parsed object would
// explode every inline array (this file keeps "keywords": ["framework", ...])
// and bury the real change in formatting noise. Each block keeps its own
// indentation and comma layout.
const composerPath = path.join(ROOT, 'composer.json');
const composerRaw = fs.readFileSync(composerPath, 'utf8');

function editComposerBlock(raw, blockName, { set = {}, add = {}, del = [] }) {
    const changes = [];
    const open = new RegExp('("' + blockName + '"\\s*:\\s*\\{)([\\s\\S]*?)(\\n(\\s*)\\})');
    const m = raw.match(open);
    if (!m) return { raw, changes, missing: Object.keys(add) };

    // Indentation of the first real entry - not of the block, whose leading
    // whitespace includes the newline after "{".
    const firstEntryLine = m[2].split('\n').find((l) => l.trim()) || '';
    const indent = (firstEntryLine.match(/^[ \t]*/) || ['    '])[0];
    const lines = m[2].split('\n').filter((l) => l.trim());

    const entries = lines.map((line) => {
        const e = line.match(/^\s*"([^"]+)"\s*:\s*"([^"]*)"\s*,?\s*$/);
        return e ? { key: e[1], value: e[2] } : null;
    }).filter(Boolean);

    const missing = [];
    const out = [];
    for (const entry of entries) {
        if (del.includes(entry.key)) {
            changes.push([entry.key, entry.value, '(removed)']);
            continue;
        }
        if (entry.key in set) {
            changes.push([entry.key, entry.value, set[entry.key]]);
            out.push({ key: entry.key, value: set[entry.key] });
            continue;
        }
        out.push(entry);
    }

    for (const [pkg, version] of Object.entries(add)) {
        if (out.some((e) => e.key === pkg)) continue;
        // Keep composer's alphabetical order inside the block.
        const at = out.findIndex((e) => e.key.localeCompare(pkg) > 0);
        const entry = { key: pkg, value: version };
        if (at === -1) out.push(entry); else out.splice(at, 0, entry);
        changes.push([pkg, '(absent)', version]);
    }

    const body = '\n' + out
        .map((e, i) => indent + '"' + e.key + '": "' + e.value + '"' + (i < out.length - 1 ? ',' : ''))
        .join('\n');

    // Re-attach everything around the block - m[0] is only the block itself.
    const rebuilt = raw.slice(0, m.index) + m[1] + body + m[3] + raw.slice(m.index + m[0].length);
    return { raw: rebuilt, changes, missing };
}

let composerAfter = composerRaw;
const versionChanges = [];
const missingPackages = [];

for (const [blockName, spec, addKey] of [
    ['require', plan.require || {}, 'add'],
    ['require-dev', { ...(plan.requireDev || {}) }, 'addDev'],
]) {
    const res = editComposerBlock(composerAfter, blockName, {
        set: spec,
        add: plan[addKey] || {},
        del: blockName === 'require' || blockName === 'require-dev' ? plan.del : [],
    });
    composerAfter = res.raw;
    versionChanges.push(...res.changes);
    missingPackages.push(...res.missing);
}

if (composerAfter !== composerRaw) {
    try {
        JSON.parse(composerAfter);
    } catch (e) {
        console.error('internal error: the edit produced invalid JSON (' + e.message + ')');
        process.exit(3);
    }
    edits.unshift({
        file: 'composer.json',
        before: composerRaw,
        after: composerAfter,
        note: 'composer.json versions',
    });
}

// --------------------------------------------------------------- reporting
console.log('== step ' + STEP + ': ' + plan.label);
console.log(APPLY ? '   mode: APPLY (writing files)\n' : '   mode: dry run - pass --apply to write\n');

console.log('   composer.json');
versionChanges.forEach(([pkg, from, to]) => {
    console.log('     ' + pkg.padEnd(34) + String(from).padEnd(12) + ' -> ' + to);
});
if (!versionChanges.length) console.log('     (already at the target versions)');
if (missingPackages.length) {
    console.log('\n     not present in this project: ' + [...new Set(missingPackages)].join(', '));
}
if (versionChanges.some(([, , to]) => to === '(absent)')) {
    console.log('     (a package listed as absent is being ADDED by this step)');
}

console.log('\n   app code');
if (!edits.some((e) => e.file !== 'composer.json')) {
    console.log('     (nothing to change for this step)');
}
edits.filter((e) => e.file !== 'composer.json').forEach((e) => console.log('     ' + e.file + ': ' + e.note));

if (plan.after && plan.after.length) {
    console.log('\n   manual follow-ups');
    plan.after.forEach((a) => console.log('     - ' + a));
}

// ------------------------------------------------------------------ diffing
let printedDiff = false;
for (const e of edits) {
    const tmpA = path.join(require('os').tmpdir(), 'l11-a-' + process.pid);
    const tmpB = path.join(require('os').tmpdir(), 'l11-b-' + process.pid);
    fs.writeFileSync(tmpA, e.before);
    fs.writeFileSync(tmpB, e.after);
    let diff = '';
    try {
        diff = execFileSync('diff', ['-u', '--label', 'a/' + e.file, '--label', 'b/' + e.file, tmpA, tmpB], { encoding: 'utf8' });
    } catch (err) {
        diff = err.stdout || '';
    }
    fs.unlinkSync(tmpA);
    fs.unlinkSync(tmpB);
    if (diff) {
        if (!printedDiff) { console.log('\n' + '='.repeat(70)); printedDiff = true; }
        console.log(diff.trimEnd());
    }
}

// ------------------------------------------------------------------- write
if (APPLY && edits.length) {
    for (const e of edits) fs.writeFileSync(path.join(ROOT, e.file), e.after);
    console.log('\n' + '='.repeat(70));
    console.log('written: ' + edits.map((e) => e.file).join(', '));
} else if (APPLY) {
    console.log('\n' + '='.repeat(70));
    console.log('nothing to write - step ' + STEP + ' already applied.');
}

// ------------------------------------------------------------------- next
console.log(`
${'='.repeat(70)}
Next, on a machine with Packagist access (the lock file and vendor/ are the
deploy artifact - all three must land in ONE commit):

    composer update ${(plan.require && plan.require['laravel/framework']) ? 'laravel/framework ' : ''}--with-all-dependencies
    composer install --no-dev --optimize-autoloader

    git add composer.json composer.lock vendor/
    git commit -m "Upgrade to ${plan.label}"

Then run the step-5 verification set from docs/LARAVEL-11-UPGRADE.md:

    vendor/bin/phpunit
    bash tools/laravel11-check.sh
    node tools/ui-check.cjs && bash tools/ui-guard.sh && php tools/ui-blade-check.php
    php artisan config:cache && php artisan route:cache && php artisan view:cache
`);

if (!APPLY) {
    console.log('(dry run - no files were changed)');
}
