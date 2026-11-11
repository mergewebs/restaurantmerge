// Use the form-submit-lock.php fixture; run await runAutoFilterTests() on a fresh page.
window.runAutoFilterTests = async () => {
    const results = [];
    const assert = (value, message) => { if (!value) throw new Error(message); };
    const test = async (name, run) => {
        try { await run(); results.push({ name, passed: true }); }
        catch (error) { results.push({ name, passed: false, error: error.message }); }
    };
    const until = (condition) => new Promise((resolve, reject) => {
        if (condition()) return resolve();
        const observer = new MutationObserver(() => {
            if (!condition()) return;
            observer.disconnect();
            clearTimeout(timeout);
            resolve();
        });
        const timeout = setTimeout(() => { observer.disconnect(); reject(new Error('Timed out')); }, 3000);
        observer.observe(document.body, { subtree: true, childList: true, attributes: true });
    });
    const withClock = (run) => {
        const original = { set: window.setTimeout, clear: window.clearTimeout };
        const timers = new Map();
        let now = 0;
        let id = 0;
        window.setTimeout = (callback, delay = 0, ...args) => {
            timers.set(++id, { due: now + Number(delay), callback: () => callback(...args) });
            return id;
        };
        window.clearTimeout = (key) => timers.delete(key);
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
        try { return run(advance); }
        finally { window.setTimeout = original.set; window.clearTimeout = original.clear; }
    };
    const setup = (fields = '<input name="search"><select name="type"><option value="">All</option><option>takeaway</option></select>', extra = '') => {
        const region = document.querySelector('[data-async-region="test"]');
        if (region._asyncRequest) {
            region._asyncRequest.controller.abort();
            window.RestaurantFormSubmit.releaseFilter(region._asyncRequest.form);
            region._asyncRequest = null;
        }
        region.innerHTML = `<form id="test-filter" method="GET" action="/filters" data-auto-submit-form>${fields}</form>${extra}`;
        window.RestaurantFilters.initialize(region);
        return { region, form: region.querySelector('form') };
    };
    const input = (field, value, composing = false) => {
        field.value = value;
        field.dispatchEvent(new InputEvent('input', { bubbles: true, isComposing: composing }));
    };
    const change = (field, value) => {
        field.value = value;
        field.dispatchEvent(new Event('change', { bubbles: true }));
    };
    const html = (label, value = '') => `<div data-async-region="test" data-async-mutations="true"><p>${label}</p><form id="test-filter" method="GET" action="/filters" data-auto-submit-form><input name="search" value="${value}"></form></div>`;

    await test('Search waits 700ms after the last keystroke, not the first', () => withClock((advance) => {
        const { form } = setup();
        const count = window.requests.length;
        input(form.elements.search, 'a');
        advance(600);
        input(form.elements.search, 'ab');
        advance(699);
        assert(window.requests.length === count, 'Search submitted too early');
        advance(1);
        assert(window.requests.length === count + 1, 'Debounced search did not submit once');
        assert(new URL(window.requests.at(-1).url).searchParams.get('search') === 'ab', 'Stale text submitted');
    }));

    await test('Selects apply immediately, preserve the search, and cancel its pending timer', () => withClock((advance) => {
        const { form } = setup();
        const count = window.requests.length;
        input(form.elements.search, 'guest');
        change(form.elements.type, 'takeaway');
        advance(800);
        assert(window.requests.length === count + 1, 'Immediate change caused duplicate requests');
        const query = new URL(window.requests.at(-1).url).searchParams;
        assert(query.get('search') === 'guest' && query.get('type') === 'takeaway', 'Combined filters lost');
    }));

    await test('Unchanged values do not request; clearing an active search does', () => withClock((advance) => {
        const { form } = setup('<input name="search" value="guest">');
        const count = window.requests.length;
        input(form.elements.search, 'guest');
        advance(700);
        assert(window.requests.length === count, 'Unchanged search submitted');
        input(form.elements.search, '');
        advance(700);
        assert(window.requests.length === count + 1 && new URL(window.requests.at(-1).url).searchParams.get('search') === '', 'Clear did not apply');
    }));

    await test('IME composition is not submitted mid-word', () => withClock((advance) => {
        const { form } = setup();
        const field = form.elements.search;
        const count = window.requests.length;
        field.dispatchEvent(new CompositionEvent('compositionstart', { bubbles: true }));
        input(field, '日本', true);
        advance(1000);
        assert(window.requests.length === count, 'Composition submitted prematurely');
        field.dispatchEvent(new CompositionEvent('compositionend', { bubbles: true }));
        advance(700);
        assert(window.requests.length === count + 1, 'Completed composition not submitted');
    }));

    await test('Custom ranges wait for both dates while optional one-sided ranges still work', () => {
        let fixture = setup('<input type="hidden" name="mode" value="custom"><div data-filter-complete-range="custom"><input type="date" name="date_from"><input type="date" name="date_to"></div>');
        const count = window.requests.length;
        change(fixture.form.elements.date_from, '2026-01-01');
        assert(window.requests.length === count, 'Incomplete custom range submitted');
        change(fixture.form.elements.date_to, '2026-01-31');
        assert(window.requests.length === count + 1, 'Complete range did not apply');
        fixture = setup('<input type="date" name="date_from"><input type="date" name="date_to">');
        change(fixture.form.elements.date_from, '2026-02-01');
        assert(window.requests.length === count + 2, 'Optional upper date became required');
    });

    await test('Separate finance search/range forms retain their combined values', () => withClock((advance) => {
        const { region, form } = setup('<input name="history_query"><input type="hidden" name="mode" value="today">', '<form id="related" method="GET" action="/filters" data-auto-submit-form><input type="hidden" name="history_query"><select name="mode"><option>today</option><option>monthly</option></select></form>');
        input(form.elements.history_query, 'rent');
        const related = region.querySelector('#related');
        change(related.elements.mode, 'monthly');
        const request = window.requests.at(-1);
        advance(800);
        assert(window.requests.at(-1) === request, 'Stale finance search timer fired');
        const query = new URL(request.url).searchParams;
        assert(query.get('history_query') === 'rent' && query.get('mode') === 'monthly', 'Finance filters diverged');
    }));

    await test('Late responses are ignored and search focus/caret survive replacement', async () => {
        let old;
        let latest;
        withClock((advance) => {
            const { form } = setup();
            input(form.elements.search, 'a');
            advance(700);
            old = window.requests.at(-1);
            input(form.elements.search, 'ab');
            assert(old.options.signal.aborted, 'Old read request not cancelled on typing');
            advance(700);
            latest = window.requests.at(-1);
            form.elements.search.focus();
            form.elements.search.setSelectionRange(1, 1);
        });
        old.resolve(new Response(html('Stale', 'a')));
        latest.resolve(new Response(html('Latest', 'ab')));
        await until(() => document.querySelector('[data-async-region="test"]').textContent.includes('Latest'));
        const field = document.querySelector('#test-filter input');
        assert(!document.querySelector('[data-async-region="test"]').textContent.includes('Stale'), 'Old results won');
        assert(field === document.activeElement && field.selectionStart === 1, 'Typing focus/caret lost');
    });

    await test('Filtering cannot cancel an in-flight save or payment', async () => {
        let mutation;
        withClock((advance) => {
            const { form, region } = setup(undefined, '<form method="POST" action="/save"><button>Save</button></form>');
            const save = region.querySelector('form[method="POST"]');
            save.requestSubmit();
            mutation = window.requests.at(-1);
            const count = window.requests.length;
            input(form.elements.search, 'new filter');
            advance(700);
            assert(!mutation.options.signal.aborted && window.requests.length === count, 'Filter interrupted the mutation');
        });
        mutation.resolve(new Response(html('Saved')));
        await until(() => document.querySelector('[data-async-region="test"]').textContent.includes('Saved'));
    });

    document.getElementById('results').textContent = JSON.stringify(results, null, 2);
    return { passed: results.filter((result) => result.passed).length, total: results.length, results };
};
