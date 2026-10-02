const { defineConfig } = require('@playwright/test');
module.exports = defineConfig({
    testDir: './tools/browser',
    use: { headless: true, launchOptions: process.env.MM_CHROMIUM_PATH ? { executablePath: process.env.MM_CHROMIUM_PATH, args: ['--no-sandbox'] } : {} },
    reporter: 'list',
});
