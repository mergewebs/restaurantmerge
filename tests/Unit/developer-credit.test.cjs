const assert = require('node:assert/strict');
const { readFileSync } = require('node:fs');
const path = require('node:path');
const test = require('node:test');

test('admin, login and public footers credit Merge with the correct secure link', () => {
    const views = path.resolve(__dirname, '../../resources/views');
    for (const layout of ['app', 'guest']) {
        const source = readFileSync(path.join(views, `layouts/${layout}.blade.php`), 'utf8');
        assert.match(source, /<footer\b[^>]*>[\s\S]*?@include\('partials\.developer-credit'\)[\s\S]*?<\/footer>/);
    }
    for (const file of ['partials/developer-credit.blade.php', 'welcome.blade.php']) {
        const source = readFileSync(path.join(views, file), 'utf8');
        assert.match(source, /Developed by\s*<a[^>]*href="https:\/\/mergewebs\.com\/"[^>]*>Merge<\/a>/);
        assert.match(source, /href="https:\/\/mergewebs\.com\/"[^>]*target="_blank"[^>]*rel="noopener noreferrer"/);
    }
});
