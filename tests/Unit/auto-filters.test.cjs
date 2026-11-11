const assert = require('node:assert/strict');
const { readFileSync } = require('node:fs');
const path = require('node:path');
const test = require('node:test');
const vm = require('node:vm');

const views = path.resolve(__dirname, '../../resources/views');
const source = readFileSync(path.join(views, 'partials/auto-filters.blade.php'), 'utf8').match(/<script>([\s\S]*)<\/script>/)[1];

function fixture(definitions = [{ fields: [{ name: 'search' }] }]) {
    const listeners = {};
    const lifecycle = {};
    const timers = new Map();
    let now = 0;
    let timerId = 0;
    const classList = () => ({
        values: new Set(),
        contains(value) { return this.values.has(value); },
        remove(value) { this.values.delete(value); },
        toggle(value, on) { if (on) this.values.add(value); else this.values.delete(value); },
    });
    class Input {
        constructor(options) {
            Object.assign(this, { name: '', value: '', type: 'text', disabled: false, dataset: {}, validity: { valid: true } }, options);
        }
        hasAttribute(name) { return name === 'data-auto-submit-search' && 'autoSubmitSearch' in this.dataset; }
    }
    class Select extends Input {}
    class Form {
        constructor(options) {
            this.method = options.method || 'GET';
            this.isConnected = true;
            this.dataset = options.dataset || {};
            this.optIn = options.optIn !== false;
            this.submissions = [];
            this.panels = [];
            this.elements = options.fields.map((field) => {
                const control = field.type === 'select' ? new Select(field) : new Input(field);
                control.form = this;
                return control;
            });
            this.elements.namedItem = (name) => this.elements.find((field) => field.name === name);
        }
        matches() { return this.optIn; }
        closest() { return this.region; }
        checkValidity() { return this.elements.every((field) => field.validity.valid); }
        requestSubmit() { this.submissions.push([...new FormData(this)]); }
        querySelectorAll(selector) {
            if (selector.includes('data-filter-complete-range')) return this.panels;
            if (selector === 'input[type="hidden"]') return this.elements.filter((field) => field.type === 'hidden');
            if (selector === '[data-range-toggle-target]') return this.elements.filter((field) => field.dataset.rangeToggleTarget);
            return [];
        }
    }
    class FormData {
        constructor(form) { this.entries = form.elements.filter((field) => field.name && !field.disabled).map((field) => [field.name, field.value]); }
        [Symbol.iterator]() { return this.entries[Symbol.iterator](); }
    }
    const forms = definitions.map((definition) => new Form(definition));
    const panels = [];
    const released = [];
    const region = { querySelectorAll: () => forms.filter((form) => form.optIn), classList: classList(), dataset: {} };
    forms.forEach((form, index) => {
        form.region = region;
        if (definitions[index].range) {
            const range = definitions[index].range;
            const panel = { dataset: range, classList: classList(), querySelectorAll: () => form.elements.filter((field) => field.type === 'date') };
            form.panels.push(panel);
            panels.push(panel);
        }
    });
    const document = {
        readyState: 'complete',
        querySelectorAll: (selector) => selector === '[data-range-toggle-panel]' ? panels : forms.filter((form) => form.optIn),
        addEventListener: (name, listener) => { (listeners[name] ||= []).push(listener); },
    };
    const window = {
        RestaurantFormSubmit: { releaseFilter: (form) => released.push(form) },
        addEventListener: (name, listener) => { lifecycle[name] = listener; },
    };
    // Selects are not HTMLInputElements in the browser.
    const HTMLInputElement = { [Symbol.hasInstance]: (field) => field instanceof Input && !(field instanceof Select) };
    const context = vm.createContext({
        document, window, HTMLFormElement: Form, HTMLInputElement, FormData, URLSearchParams,
        setTimeout: (callback, delay) => { timers.set(++timerId, { callback, due: now + delay }); return timerId; },
        clearTimeout: (id) => timers.delete(id),
    });
    vm.runInContext(source, context);
    const emit = (name, field, options = {}) => (listeners[name] || []).forEach((listener) => listener({ target: field, ...options }));
    const advance = (ms) => {
        const end = now + ms;
        for (;;) {
            const next = [...timers].filter(([, timer]) => timer.due <= end).sort((a, b) => a[1].due - b[1].due)[0];
            if (!next) break;
            timers.delete(next[0]);
            now = next[1].due;
            next[1].callback();
        }
        now = end;
    };
    return { forms, region, released, window, lifecycle, emit, advance, timers };
}

test('search uses a trailing 700ms debounce', () => {
    const f = fixture();
    const form = f.forms[0];
    const field = form.elements[0];
    field.value = 'a'; f.emit('input', field); f.advance(600);
    field.value = 'ab'; f.emit('input', field); f.advance(699);
    assert.equal(form.submissions.length, 0);
    f.advance(1);
    assert.deepEqual(form.submissions, [[['search', 'ab']]]);
});

test('select applies immediately and cancels a pending search', () => {
    const f = fixture([{ fields: [{ name: 'search' }, { name: 'type', type: 'select' }] }]);
    const form = f.forms[0];
    form.elements[0].value = 'guest'; f.emit('input', form.elements[0]);
    form.elements[1].value = 'takeaway'; f.emit('change', form.elements[1]);
    f.advance(800);
    assert.deepEqual(form.submissions, [[['search', 'guest'], ['type', 'takeaway']]]);
});

test('unchanged values are skipped and clearing an active search submits', () => {
    const f = fixture([{ fields: [{ name: 'search', value: 'guest' }] }]);
    const form = f.forms[0];
    f.emit('input', form.elements[0]); f.advance(700);
    assert.equal(form.submissions.length, 0);
    form.elements[0].value = ''; f.emit('input', form.elements[0]); f.advance(700);
    assert.deepEqual(form.submissions, [[['search', '']]]);
});

test('IME composition waits for completion and the typing delay', () => {
    const f = fixture();
    const form = f.forms[0];
    const field = form.elements[0];
    f.emit('compositionstart', field);
    field.value = '日本'; f.emit('input', field, { isComposing: true }); f.advance(1000);
    assert.equal(form.submissions.length, 0);
    f.emit('compositionend', field); f.advance(699);
    assert.equal(form.submissions.length, 0);
    f.advance(1);
    assert.equal(form.submissions.length, 1);
});

test('custom ranges wait for both dates; optional history bounds stay optional', () => {
    const fields = [{ name: 'mode', type: 'hidden', value: 'custom' }, { name: 'date_from', type: 'date' }, { name: 'date_to', type: 'date' }];
    const f = fixture([{ fields, range: { filterCompleteRange: 'custom' } }]);
    const form = f.forms[0];
    form.elements[1].value = '2026-01-01'; f.emit('change', form.elements[1]);
    assert.equal(form.submissions.length, 0);
    form.elements[2].value = '2026-01-31'; f.emit('change', form.elements[2]);
    assert.equal(form.submissions.length, 1);
    const history = fixture([{ fields: fields.slice(1) }]);
    history.forms[0].elements[0].value = '2026-02-01'; history.emit('change', history.forms[0].elements[0]);
    assert.equal(history.forms[0].submissions.length, 1);
});

test('preset ranges ignore incomplete inactive custom fields', () => {
    const f = fixture([{ fields: [{ name: 'mode', type: 'select', value: 'custom' }, { name: 'date_from', type: 'date' }, { name: 'date_to', type: 'date' }], range: { filterCompleteRange: 'custom' } }]);
    f.forms[0].elements[0].value = 'monthly'; f.emit('change', f.forms[0].elements[0]);
    assert.equal(f.forms[0].submissions.length, 1);
});

test('custom date edits do not submit a temporarily inverted range', () => {
    const f = fixture([{ fields: [{ name: 'mode', type: 'hidden', value: 'custom' }, { name: 'date_from', type: 'date', value: '2026-01-01' }, { name: 'date_to', type: 'date', value: '2026-01-31' }], range: { filterCompleteRange: 'custom' } }]);
    const form = f.forms[0];
    form.elements[1].value = '2026-02-01'; f.emit('change', form.elements[1]);
    assert.equal(form.submissions.length, 0);
    form.elements[2].value = '2026-02-28'; f.emit('change', form.elements[2]);
    assert.equal(form.submissions.length, 1);
});

test('finance forms synchronize hidden fields and share cancellation of debounce timers', () => {
    const f = fixture([
        { fields: [{ name: 'history_query' }, { name: 'mode', type: 'hidden', value: 'today' }] },
        { fields: [{ name: 'history_query', type: 'hidden' }, { name: 'mode', type: 'select', value: 'today' }] },
    ]);
    f.forms[0].elements[0].value = 'rent'; f.emit('input', f.forms[0].elements[0]);
    f.forms[1].elements[1].value = 'monthly'; f.emit('change', f.forms[1].elements[1]); f.advance(800);
    assert.equal(f.forms[0].submissions.length, 0);
    assert.deepEqual(f.forms[1].submissions, [[['history_query', 'rent'], ['mode', 'monthly']]]);
    assert.equal(f.forms[0].elements[1].value, 'monthly');
});

test('typing aborts only stale GET requests and releases their form lock', () => {
    const f = fixture();
    const controller = new AbortController();
    f.region._asyncRequest = { controller, method: 'GET', form: f.forms[0] };
    f.forms[0].elements[0].value = 'new'; f.emit('input', f.forms[0].elements[0]);
    assert.equal(controller.signal.aborted, true);
    assert.deepEqual(f.released, [f.forms[0]]);
    const mutation = new AbortController();
    f.region._asyncRequest = { controller: mutation, method: 'POST' };
    f.emit('input', f.forms[0].elements[0]);
    assert.equal(mutation.signal.aborted, false);
});

test('POST forms, disabled fields, invalid fields and detached forms never auto-submit', () => {
    const post = fixture([{ method: 'POST', fields: [{ name: 'search' }] }]);
    post.forms[0].elements[0].value = 'x'; post.emit('input', post.forms[0].elements[0]); post.advance(800);
    assert.equal(post.forms[0].submissions.length, 0);
    for (const option of ['disabled', 'invalid', 'detached']) {
        const f = fixture();
        const form = f.forms[0];
        const field = form.elements[0];
        field.value = 'x';
        if (option === 'disabled') field.disabled = true;
        if (option === 'invalid') field.validity.valid = false;
        if (option === 'detached') form.isConnected = false;
        f.emit('input', field); f.advance(800);
        assert.equal(form.submissions.length, 0, option);
    }
});

test('leaving a page cancels pending searches', () => {
    const f = fixture();
    f.forms[0].elements[0].value = 'x'; f.emit('input', f.forms[0].elements[0]);
    f.lifecycle.pagehide(); f.advance(800);
    assert.equal(f.forms[0].submissions.length, 0);
});

test('all affected inline JavaScript parses without Laravel or a browser', () => {
    for (const file of ['partials/auto-filters.blade.php', 'partials/form-submit-lock.blade.php', 'layouts/app.blade.php']) {
        const scripts = [...readFileSync(path.join(views, file), 'utf8').matchAll(/<script>([\s\S]*?)<\/script>/g)];
        assert.ok(scripts.length);
        scripts.forEach(([, script]) => new vm.Script(script, { filename: file }));
    }
});

function asyncRegionFixture() {
    const app = readFileSync(path.join(views, 'layouts/app.blade.php'), 'utf8');
    const start = app.indexOf('const submitRegionRequest =');
    const end = app.indexOf('const initializeAsyncRegions =', start);
    assert.ok(start > 0 && end > start);
    const requests = [];
    const replacements = [];
    const failures = [];
    const classes = new Set();
    const region = {
        dataset: { asyncRegion: 'test' }, isConnected: true,
        classList: { add: (name) => classes.add(name), remove: (name) => classes.delete(name) },
    };
    const context = vm.createContext({
        AbortController,
        HTMLElement: class {},
        document: { title: 'Test' },
        ensureLoadingBadge: () => {},
        showToast: () => {},
        parseHtml: (html) => ({ title: html, querySelector: () => ({ label: html, querySelector: () => null }) }),
        replaceRegion: (_, next) => { replacements.push(next.label); return next; },
        window: {
            location: { assign: () => {} }, history: { replaceState: () => {} },
            RestaurantFormSubmit: { fail: (form) => failures.push(form), releaseFilter: () => {} },
        },
        fetch: (url, options) => new Promise((resolve, reject) => requests.push({ url, options, resolve, reject })),
    });
    vm.runInContext(app.slice(start, end) + '\nglobalThis.send = submitRegionRequest;', context);
    const respond = (request, html) => request.resolve({ ok: true, url: '/filters', text: async () => html });
    return { region, requests, replacements, failures, classes, send: context.send, respond };
}

test('late read responses cannot overwrite newer results', async () => {
    const f = asyncRegionFixture();
    const old = f.send(f.region, { method: 'GET', url: '/old', form: {} });
    const latest = f.send(f.region, { method: 'GET', url: '/new', form: {} });
    assert.equal(f.requests[0].options.signal.aborted, true);
    f.respond(f.requests[1], 'Latest'); await latest;
    f.respond(f.requests[0], 'Stale'); await old;
    assert.deepEqual(f.replacements, ['Latest']);
});

test('stale errors neither unlock a newer submission nor clear its loading indicator', async () => {
    const f = asyncRegionFixture();
    const old = f.send(f.region, { method: 'GET', url: '/old', form: { id: 'old' } });
    const latestForm = { id: 'latest' };
    const latest = f.send(f.region, { method: 'GET', url: '/new', form: latestForm });
    f.requests[0].reject(new Error('Old request failed')); await old;
    assert.deepEqual(f.failures, []);
    assert.equal(f.classes.has('is-async-loading'), true);
    f.requests[1].reject(new Error('Current request failed')); await latest;
    assert.deepEqual(f.failures, [latestForm]);
    assert.equal(f.classes.has('is-async-loading'), false);
});

test('a filter request never aborts or races a save/payment request', async () => {
    const f = asyncRegionFixture();
    const save = f.send(f.region, { method: 'POST', url: '/save', form: {} });
    const filter = { id: 'filter' };
    await f.send(f.region, { method: 'GET', url: '/filters', form: filter });
    assert.equal(f.requests.length, 1);
    assert.equal(f.requests[0].options.signal.aborted, false);
    assert.deepEqual(f.failures, [filter]);
    f.respond(f.requests[0], 'Saved'); await save;
});
