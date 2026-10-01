/** Isolated Blade renovation layer. Legacy Bootstrap/Ace remains untouched. */
module.exports = {
    content: ['./resources/views/**/*.blade.php', './module/**/views/**/*.blade.php'],
    prefix: 'tw-',
    important: '.mm-ui',
    corePlugins: { preflight: false },
    theme: {
        // Ace sets html to 10px. Pixel-based scales avoid accidentally shrinking
        // Tailwind's rem-based defaults while both systems coexist.
        spacing: { 0: '0px', 1: '4px', 2: '8px', 3: '12px', 4: '16px', 5: '20px', 6: '24px', 8: '32px', 10: '40px', 12: '48px' },
        fontSize: { xs: ['12px', '18px'], sm: ['14px', '20px'], base: ['16px', '24px'], xl: ['24px', '32px'], '2xl': ['30px', '38px'] },
        borderRadius: { none: '0px', DEFAULT: '6px', lg: '10px', xl: '16px', full: '9999px' },
        extend: {
            colors: { brand: 'var(--mm-brand)', ink: 'var(--mm-ink)', muted: 'var(--mm-muted)', line: 'var(--mm-line)', canvas: 'var(--mm-bg)', surface: 'var(--mm-surface)' },
            fontFamily: { sans: ['system-ui', '-apple-system', 'Segoe UI', 'sans-serif'] },
        },
    },
    plugins: [],
};
