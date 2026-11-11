// Rasterize the existing restaurant-mark.svg; no new artwork or runtime dependency.
// Set PLAYWRIGHT_MODULE to an installed Playwright package if it is not on NODE_PATH.
const { chromium } = require(process.env.PLAYWRIGHT_MODULE || 'playwright');
const fs = require('node:fs/promises');
const path = require('node:path');

(async () => {
    const browser = await chromium.launch({ channel: 'chrome', headless: true });
    try {
        const page = await browser.newPage();
        const svg = await fs.readFile(path.join(__dirname, '../../public/restaurant-mark.svg'), 'utf8');
        const output = path.join(__dirname, '../../public/pwa/icons');
        await fs.mkdir(output, { recursive: true });
        for (const [name, size, scale] of [
            ['icon-192', 192, 1], ['icon-512', 512, 1],
            ['icon-maskable-512', 512, .8], ['apple-touch-icon', 180, 1],
        ]) {
            const data = await page.evaluate(async ({ svg, size, scale }) => {
                const img = new Image();
                img.src = `data:image/svg+xml;base64,${btoa(svg)}`;
                await img.decode();
                const canvas = document.createElement('canvas');
                canvas.width = canvas.height = size;
                const context = canvas.getContext('2d');
                context.fillStyle = '#271c16';
                context.fillRect(0, 0, size, size);
                const inset = size * (1 - scale) / 2;
                context.drawImage(img, inset, inset, size * scale, size * scale);
                return canvas.toDataURL('image/png').split(',')[1];
            }, { svg, size, scale });
            await fs.writeFile(path.join(output, `${name}.png`), Buffer.from(data, 'base64'));
        }
    } finally {
        await browser.close();
    }
})().catch((error) => { console.error(error); process.exitCode = 1; });
