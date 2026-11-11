const assert = require('node:assert/strict');
const { readFileSync } = require('node:fs');
const path = require('node:path');
const test = require('node:test');
const vm = require('node:vm');

const source = readFileSync(path.resolve(__dirname, '../../resources/views/partials/toast-notifications.blade.php'), 'utf8').match(/<script>([\s\S]*)<\/script>/)[1];

function fixture() {
    const timers = new Map();
    const lifecycle = {};
    let time = 0;
    let id = 0;
    class Element {
        constructor(tag = 'div') { this.tagName = tag.toUpperCase(); this.children = []; this.dataset = {}; this.style = {}; this.attributes = {}; this.listeners = {}; this.className = ''; this.value = ''; }
        append(...nodes) { nodes.forEach((node) => { node.parent = this; this.children.push(node); }); }
        remove() { if (this.parent) this.parent.children = this.parent.children.filter((node) => node !== this); this.parent = null; }
        contains(node) { return node === this || this.children.some((child) => child.contains(node)); }
        get isConnected() { return document.body?.contains(this) || false; }
        setAttribute(name, value) {
            this.attributes[name] = value;
            if (name.startsWith('data-')) this.dataset[name.slice(5).replace(/-([a-z])/g, (_, letter) => letter.toUpperCase())] = value;
        }
        getAttribute(name) { return this.attributes[name] ?? null; }
        removeAttribute(name) { delete this.attributes[name]; }
        matches(selector) {
            return selector.split(',').some((part) => {
                const match = part.trim();
                if (match.startsWith('.')) return this.className.split(/\s+/).includes(match.slice(1));
                if (match.startsWith('[')) return this.getAttribute(match.slice(1, -1)) !== null;
                return this.tagName.toLowerCase() === match.split('[')[0];
            });
        }
        closest(selector) { return this.matches(selector) ? this : this.parent?.closest(selector); }
        querySelectorAll(selector) { return this.children.flatMap((child) => [...(child.matches(selector) ? [child] : []), ...child.querySelectorAll(selector)]); }
        querySelector(selector) { return this.querySelectorAll(selector)[0] || null; }
        addEventListener(name, listener) { (this.listeners[name] ||= []).push(listener); }
        emit(name, options = {}) { (this.listeners[name] || []).forEach((listener) => listener({ target: this, ...options })); }
        focus() { document.activeElement = this; }
        set textContent(value) { this.text = String(value); this.children = []; }
        get textContent() { return this.text ?? this.children.map((node) => node.textContent).join(''); }
        set innerHTML(markup) {
            this.markup = markup;
            const body = new Element(); body.className = 'accent-toast-body';
            for (const name of ['accent-toast-title', 'accent-toast-text']) { const node = new Element('span'); node.className = name; body.append(node); }
            const close = new Element('button'); close.className = 'accent-toast-close';
            const progress = new Element('span'); progress.className = 'accent-toast-progress';
            this.append(body, close, progress);
        }
    }
    class Form extends Element { constructor() { super('form'); } }
    const body = new Element('body');
    const document = {
        body, activeElement: body, readyState: 'complete', hidden: false, listeners: {},
        createElement: (tag) => new Element(tag),
        querySelector: (selector) => body.querySelector(selector),
        querySelectorAll: (selector) => body.querySelectorAll(selector),
        addEventListener(name, listener) { (this.listeners[name] ||= []).push(listener); },
        emit(name, options = {}) { (this.listeners[name] || []).forEach((listener) => listener(options)); },
    };
    const window = { addEventListener: (name, listener) => { lifecycle[name] = listener; } };
    const context = vm.createContext({
        window, document, Element, HTMLFormElement: Form, performance: { now: () => time },
        setTimeout: (callback, delay) => { timers.set(++id, { callback, due: time + delay }); return id; },
        clearTimeout: (key) => timers.delete(key),
    });
    vm.runInContext(source, context);
    const advance = (ms) => {
        const end = time + ms;
        for (;;) {
            const next = [...timers].filter(([, timer]) => timer.due <= end).sort((a, b) => a[1].due - b[1].due)[0];
            if (!next) break;
            timers.delete(next[0]); time = next[1].due; next[1].callback();
        }
        time = end;
    };
    const toasts = () => body.querySelectorAll('.accent-toast');
    return { document, body, Element, Form, lifecycle, advance, toasts, api: window.RestaurantNotifications };
}

test('success/info dismiss after 6 seconds and errors/warnings after 8 seconds', () => {
    for (const [level, duration] of [['success', 6000], ['info', 6000], ['error', 8000], ['warning', 8000]]) {
        const f = fixture();
        const toast = f.api.show('Saved', level);
        assert.equal(toast.getAttribute('role'), ['error', 'warning'].includes(level) ? 'alert' : 'status');
        f.advance(duration - 1); assert.equal(toast.dataset.state, undefined);
        f.advance(1); assert.equal(toast.dataset.state, 'closing');
        f.advance(200); assert.equal(f.toasts().length, 0);
    }
});

test('message and title are text, never interpolated HTML', () => {
    const f = fixture();
    const message = '<img src=x onerror=alert(1)>';
    const toast = f.api.show(message, 'error', { title: '<script>unsafe</script>' });
    assert.equal(toast.querySelector('.accent-toast-text').textContent, message);
    assert.equal(toast.querySelector('.accent-toast-title').textContent, '<script>unsafe</script>');
    assert.ok(!toast.markup.includes('onerror') && !toast.markup.includes('<script>'));
});

test('duplicates refresh one toast instead of stacking', () => {
    const f = fixture();
    f.api.show('Saved', 'success'); f.advance(4000);
    f.api.show('Saved', 'success');
    assert.equal(f.toasts().length, 1);
    f.advance(5000); assert.equal(f.toasts()[0].dataset.state, undefined);
    f.advance(1200); assert.equal(f.toasts().length, 0);
});

test('hover/focus pause the timer until both interactions finish', () => {
    const f = fixture();
    const toast = f.api.show('Read this'); f.advance(2000);
    toast.emit('mouseenter'); toast.emit('focusin');
    f.advance(10000); toast.emit('mouseleave'); f.advance(10000);
    assert.equal(toast.dataset.state, undefined);
    toast.emit('focusout', { relatedTarget: f.body }); f.advance(3999);
    assert.equal(toast.dataset.state, undefined);
    f.advance(201); assert.equal(f.toasts().length, 0);
});

test('background tabs pause countdowns', () => {
    const f = fixture();
    const toast = f.api.show('Read this'); f.advance(1000);
    f.document.hidden = true; f.document.emit('visibilitychange'); f.advance(20000);
    assert.equal(toast.dataset.state, undefined);
    f.document.hidden = false; f.document.emit('visibilitychange'); f.advance(5200);
    assert.equal(f.toasts().length, 0);
});

test('at most three notifications are visible; queued messages still appear', () => {
    const f = fixture();
    for (let i = 0; i < 5; i++) f.api.show(`Notice ${i}`);
    assert.equal(f.toasts().length, 3);
    f.advance(6200);
    assert.equal(f.toasts().length, 2);
    assert.deepEqual(f.toasts().map((toast) => toast.querySelector('.accent-toast-text').textContent), ['Notice 3', 'Notice 4']);
    f.advance(6200); assert.equal(f.toasts().length, 0);
});

test('manual close restores prior keyboard focus', () => {
    const f = fixture();
    const field = new f.Element('input'); f.body.append(field); field.focus();
    const toast = f.api.show('Done');
    const close = toast.querySelector('button'); close.focus(); close.emit('click');
    assert.equal(f.document.activeElement, field);
    f.advance(200); assert.equal(f.toasts().length, 0);
});

test('initial and AJAX feedback are consumed once and duplicate markers collapse', () => {
    const f = fixture();
    const fragment = new f.Element();
    for (let i = 0; i < 2; i++) {
        const message = new f.Element('span'); message.textContent = 'Updated';
        message.setAttribute('data-page-feedback', ''); message.dataset.feedbackLevel = 'success'; fragment.append(message);
    }
    f.api.consumeFeedback(fragment); f.api.consumeFeedback(fragment);
    assert.equal(f.toasts().length, 1);
    fragment.children.forEach((message) => { assert.equal(message.hidden, true); assert.equal(message.dataset.feedbackConsumed, 'true'); });
});

test('duration inputs are bounded and unknown levels fall back safely', () => {
    const f = fixture();
    const toast = f.api.show('Note', '__proto__', { duration: Infinity });
    assert.equal(toast.className, 'accent-toast accent-toast-info');
    assert.equal(toast.querySelector('.accent-toast-progress').style.animationDuration, '6000ms');
    const long = f.api.show('Long', 'error', { duration: 100000 });
    assert.equal(long.querySelector('.accent-toast-progress').style.animationDuration, '30000ms');
    f.api.show({ unsafe: 'not a message' }); assert.equal(f.toasts().length, 2);
});

test('native validation becomes a notification only on a user submit attempt', () => {
    const f = fixture();
    const form = new f.Form(); const button = new f.Element('button'); button.type = 'submit'; button.form = form;
    const field = new f.Element('input'); field.form = form; field.name = 'Email'; field.validationMessage = 'Please fill out this field.';
    form.append(field, button); f.body.append(form);
    let prevented = false;
    const invalid = { target: field, preventDefault: () => { prevented = true; } };
    f.document.emit('invalid', invalid); assert.equal(prevented, false); assert.equal(f.toasts().length, 0);
    f.document.emit('click', { target: button }); f.document.emit('invalid', invalid); f.advance(0);
    assert.equal(prevented, true); assert.equal(field.getAttribute('aria-invalid'), 'true');
    assert.equal(f.toasts()[0].querySelector('.accent-toast-text').textContent, 'Email: Please fill out this field.');
    assert.equal(f.document.activeElement, field);
    field.validity = { valid: true }; f.document.emit('input', { target: field });
    assert.equal(field.getAttribute('aria-invalid'), null);
});

test('navigation clears visible and queued notifications without replaying them', () => {
    const f = fixture();
    for (let i = 0; i < 5; i++) f.api.show(`Notice ${i}`);
    f.lifecycle.pagehide(); f.advance(30000);
    assert.equal(f.toasts().length, 0);
});

test('admin, login and public pages share flash/validation notifications', () => {
    const views = path.resolve(__dirname, '../../resources/views');
    for (const file of ['layouts/app.blade.php', 'layouts/guest.blade.php', 'welcome.blade.php']) {
        const template = readFileSync(path.join(views, file), 'utf8');
        assert.ok(template.includes("@include('partials.toast-notifications')"), file);
        assert.ok(template.includes("@include('partials.notification-feedback')"), file);
    }
    const feedback = readFileSync(path.join(views, 'partials/notification-feedback.blade.php'), 'utf8');
    for (const key of ['success', 'error', 'warning', 'info', 'status', 'contact_success', 'profile-updated', 'passcode-updated']) assert.ok(feedback.includes(`'${key}'`), key);
    assert.ok(feedback.includes('$errors->getBags()'), 'Named validation bags must be covered');
    const app = readFileSync(path.join(views, 'layouts/app.blade.php'), 'utf8');
    assert.ok(app.includes('consumeFeedback(documentFragment)'), 'AJAX must consume global flash messages, not just region markers');
    const contact = readFileSync(path.join(views, 'partials/public-contact-forms.blade.php'), 'utf8');
    new vm.Script(contact.match(/<script>([\s\S]*)<\/script>/)[1]);
    assert.ok(contact.includes('window.RestaurantNotifications.show'), 'Public forms must use the shared design');
});
