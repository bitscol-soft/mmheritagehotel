{{-- Native dialogs: focus trap, Esc and inert background come from the browser. Hidden until shell-tools.js enables them. --}}
<dialog class="mm-dialog mm-palette" id="mm-palette" aria-label="Command palette">
    <div class="mm-palette-box">
        <div class="mm-palette-field">
            <i class="fa fa-search" aria-hidden="true"></i>
            <input type="text" id="mm-palette-input" class="mm-palette-input" role="combobox" aria-expanded="true" aria-controls="mm-palette-list" aria-autocomplete="list" aria-label="Search screens and actions" placeholder="Search screens and actions…" autocomplete="off" spellcheck="false">
            <button type="button" class="mm-kbd mm-palette-close" data-mm-dialog-close aria-label="Close search">Esc</button>
        </div>
        <ul class="mm-palette-list" id="mm-palette-list" role="listbox" aria-label="Results"></ul>
        <p class="mm-palette-empty" id="mm-palette-empty" hidden>No matching screens or actions.</p>
        <p class="mm-palette-foot"><span><kbd class="mm-kbd">↑</kbd> <kbd class="mm-kbd">↓</kbd> move</span><span><kbd class="mm-kbd">Enter</kbd> open</span><span><kbd class="mm-kbd">Esc</kbd> close</span></p>
        <p class="mm-sr" id="mm-palette-status" role="status" aria-live="polite"></p>
    </div>
</dialog>

<dialog class="mm-dialog mm-help" id="mm-shortcuts" aria-labelledby="mm-shortcuts-title">
    <div class="mm-help-box">
        <div class="mm-help-head">
            <h2 id="mm-shortcuts-title">Keyboard shortcuts</h2>
            <button type="button" class="mm-hdr-btn" data-mm-dialog-close aria-label="Close keyboard shortcuts"><i class="fa fa-times" aria-hidden="true"></i></button>
        </div>
        <dl class="mm-help-list">
            <div><dt><kbd class="mm-kbd" data-mm-mod-key>Ctrl K</kbd> or <kbd class="mm-kbd">/</kbd></dt><dd>Search screens and actions</dd></div>
            <div><dt><kbd class="mm-kbd">[</kbd></dt><dd>Show or hide the sidebar</dd></div>
            <div><dt><kbd class="mm-kbd">?</kbd></dt><dd>Show this list</dd></div>
            <div><dt><kbd class="mm-kbd">Esc</kbd></dt><dd>Close a dialog or the mobile menu</dd></div>
        </dl>
        <p class="mm-help-note">Single-key shortcuts are ignored while typing in a field. Search only lists screens already in your sidebar, so it follows your permissions.</p>
    </div>
</dialog>
