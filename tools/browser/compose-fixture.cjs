// Composes a browser fixture: hand-built page shells plus partials rendered from the real Blade views
// (tools/ui-blade-check.php writes the shell-*.html and room-board.html parts; MM_WRITE_FIXTURE=1).
const fs = require('fs');
const path = require('path');
const root = path.resolve(__dirname, '../..');
const read = name => fs.readFileSync(path.join(root, 'tools/fixtures', name), 'utf8');
function fullMenuMarkup() {
    const html = read('full-menu.html');
    const start = html.indexOf('<ul class="nav nav-list" id="mm-primary-menu"');
    return html.slice(start, html.indexOf('</ul></div>', start) + 5);
}
function compose(name, source) {
    const parts = {
        '<!--HEADER-TOOLS-->': () => read('shell-header-tools.html'),
        '<!--SHELL-TOOLBAR-->': () => read('shell-toolbar.html'),
        '<!--SHELL-FOOTER-->': () => read('shell-footer.html'),
        '<!--SHELL-OVERLAYS-->': () => read('shell-overlays.html'),
        '<!--PRIMARY-MENU-->': fullMenuMarkup,
        '<!--ROOM-BOARD-->': () => read('room-board.html'),
    };
    let html = source !== undefined ? source : read(name);
    for (const marker of Object.keys(parts)) html = html.split(marker).join(html.includes(marker) ? parts[marker]() : '');
    return html;
}
module.exports = { compose, fullMenuMarkup };
