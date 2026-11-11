<?php

// Dependency-free browser regression fixture:
// php -S 127.0.0.1:8765 -t tests/Browser
// Open /form-submit-lock.php and run await runFormSubmitLockTests() in the browser.
$views = dirname(__DIR__, 2).'/resources/views/';
?>
<!doctype html>
<html lang="en">
<head><meta charset="utf-8"><title>Form submission regression tests</title></head>
<body>
<h1>Form submission regression tests</h1>
<pre id="results">Run window.runFormSubmitLockTests() to start.</pre>
<div id="fixtures"></div>
<div id="contact-notification"><span class="contact-notification-icon"></span><strong></strong><p></p><button type="button">Dismiss</button></div>
<form class="contact-form" action="/contact" method="POST"><input name="email" value="test@example.com" required type="email"><button type="submit"><span>Send enquiry</span></button></form>
<a href="#demo-request">Demo</a>
<div id="demo-request"><section class="demo-request-panel"><button type="button" data-demo-modal-close>Close</button><form class="contact-form demo-request-form" action="/contact" method="POST"><input name="email" value="demo@example.com" required type="email"><button type="submit">Request my demo</button></form></section></div>
<div data-async-region="test" data-async-mutations="true"><form action="/region" method="POST"><input name="value" value="test"><button>Save</button></form></div>
<div id="demo-mode-modal"><form class="demo-contact-form" action="/contact" method="POST"><input name="email" value="demo@example.com" required type="email"><button type="submit">Send request</button></form><div class="demo-contact-feedback" hidden></div></div>
<script>
    window.nativeSubmissions = [];
    HTMLFormElement.prototype.submit = function () {
        if (this.dataset.throwOnSubmit) throw new Error('Native submission failed');
        window.nativeSubmissions.push({ form: this, data: [...new FormData(this)] });
    };
    window.requests = [];
    window.fetch = (url, options) => new Promise((resolve, reject) => {
        window.requests.push({ url, options, resolve, reject });
    });
</script>
<?php
readfile($views.'partials/form-submit-lock.blade.php');
readfile($views.'partials/toast-notifications.blade.php');
readfile($views.'partials/public-contact-forms.blade.php');
readfile($views.'partials/auto-filters.blade.php');
preg_match_all('/<script>(.*?)<\/script>/s', file_get_contents($views.'layouts/app.blade.php'), $scripts);
foreach ($scripts[0] as $script) {
    if (str_contains($script, 'const initializeAsyncRegions')) echo $script;
}
$demo = file_get_contents($views.'partials/demo-mode-modal.blade.php');
preg_match('/<script>(.*?)<\/script>/s', $demo, $demoScript);
$demoScript = str_replace(["@json(route('logout'))", "@json(route('contact-submissions.store'))"], ['"/logout"', '"/contact"'], $demoScript[1]);
$demoScript = preg_replace('/@if\(session\(\'demo_action_blocked\'\)\).*?@endif/s', '', $demoScript);
?>
<script>
    window.runFormSubmitLockTests = async () => {
        const results = [];
        const assert = (condition, message) => { if (!condition) throw new Error(message); };
        const until = (condition) => new Promise((resolve, reject) => {
            if (condition()) return resolve();
            const observer = new MutationObserver(() => {
                if (!condition()) return;
                observer.disconnect();
                clearTimeout(timeout);
                resolve();
            });
            const timeout = setTimeout(() => {
                observer.disconnect();
                reject(new Error('Timed out waiting for form state'));
            }, 3000);
            observer.observe(document.body, { attributes: true, childList: true, subtree: true });
        });
        const test = async (name, run) => {
            try { await run(); results.push({ name, passed: true }); }
            catch (error) { results.push({ name, passed: false, error: error.message }); }
        };
        const makeForm = (html = '<button>Save</button>') => {
            const form = document.createElement('form');
            form.innerHTML = html;
            document.getElementById('fixtures').append(form);
            return form;
        };
        const intercept = (form) => {
            let calls = 0;
            form.addEventListener('submit', (event) => {
                event.preventDefault();
                if (window.RestaurantFormSubmit.handle(form)) calls++;
            });
            return () => calls;
        };
        const submit = (form, button = form.querySelector('button')) => form.dispatchEvent(new SubmitEvent('submit', { bubbles: true, cancelable: true, submitter: button }));
        const respond = (request, status, data) => request.resolve(new Response(JSON.stringify(data), { status, headers: { 'Content-Type': 'application/json' } }));

        await test('Immediate disable, animated spinner, default buttons, double clicks and Enter', async () => {
            const form = makeForm();
            const button = form.querySelector('button');
            const calls = intercept(form);
            button.click();
            assert(button.disabled && form.getAttribute('aria-busy') === 'true', 'Not locked synchronously');
            const spinner = button.querySelector('.form-submit-spinner');
            assert(spinner && getComputedStyle(spinner).animationName === 'formSubmitSpin', 'No spinning icon');
            button.click();
            submit(form);
            form.requestSubmit();
            assert(calls() === 1, 'Duplicate submit reached the handler');
            window.RestaurantFormSubmit.fail(form);
        });

        await test('HTML validation leaves invalid form usable and permits a valid retry', async () => {
            const form = makeForm('<input name="email" required type="email"><button>Send</button>');
            const calls = intercept(form);
            form.requestSubmit();
            assert(!form.querySelector('button').disabled && calls() === 0, 'Invalid form locked');
            form.elements.email.value = 'valid@example.com';
            form.requestSubmit();
            assert(calls() === 1 && form.querySelector('button').disabled, 'Valid retry failed');
            window.RestaurantFormSubmit.fail(form);
        });

        await test('Cancelled confirmation unlocks without losing markup, listeners or prior disabled states', async () => {
            const form = makeForm('<button><span>Save</span><svg></svg></button><button disabled>Unavailable</button>');
            const button = form.querySelector('button');
            const original = button.firstChild;
            let clicks = 0;
            original.addEventListener('click', () => clicks++);
            form.addEventListener('submit', (event) => event.preventDefault());
            submit(form);
            await until(() => !button.disabled);
            assert(!button.disabled && button.firstChild === original, 'Original DOM not restored');
            original.click();
            await until(() => !button.disabled);
            assert(clicks === 1 && form.querySelectorAll('button')[1].disabled, 'Listeners or disabled state changed');
        });

        await test('External submitters and name/value survive disabling without duplication', async () => {
            const form = makeForm('<input name="amount" value="25"><button type="submit" name="action" value="save">Save</button><button disabled>Off</button>');
            form.id = 'external-form';
            const external = document.createElement('button');
            external.setAttribute('form', form.id);
            external.name = 'action';
            external.value = 'pay';
            external.textContent = 'Pay';
            document.body.append(external);
            intercept(form);
            external.click();
            assert(external.disabled && form.querySelector('button').disabled, 'Associated buttons not disabled');
            assert(new FormData(form).getAll('action').join() === 'pay', 'Submitter name/value lost or duplicated');
            window.RestaurantFormSubmit.fail(form);
            assert(!external.disabled && !new FormData(form).has('action'), 'Submitter proxy not cleaned up');
        });

        await test('Native submit(), no-button forms and synchronous errors are guarded', async () => {
            const form = makeForm('<select name="status"><option>ready</option></select>');
            const before = window.nativeSubmissions.length;
            form.submit();
            form.submit();
            assert(window.nativeSubmissions.length === before + 1, 'Native duplicate submitted');
            const failed = makeForm();
            failed.dataset.throwOnSubmit = 'true';
            try { failed.submit(); } catch (_) { /* Expected. */ }
            assert(!failed.querySelector('button').disabled, 'Native exception left button locked');
        });

        await test('Input submit controls get a spinner and restore their value on failure', async () => {
            const form = makeForm('<input type="submit" value="Send">');
            intercept(form);
            const button = form.querySelector('input');
            form.requestSubmit(button);
            assert(button.disabled && form.querySelector('.form-submit-spinner'), 'Input control has no loading state');
            window.RestaurantFormSubmit.fail(form);
            assert(button.value === 'Send' && !button.disabled && !form.querySelector('.form-submit-spinner'), 'Input not restored');
        });

        await test('Unfocused delete triggers disable and spin for hidden programmatic forms', async () => {
            const form = makeForm('<input name="record" value="1">');
            const trigger = document.createElement('button');
            trigger.type = 'button';
            trigger.name = 'not_a_submitter';
            trigger.textContent = 'Delete';
            document.body.append(trigger);
            trigger.addEventListener('click', () => form.submit());
            const before = window.nativeSubmissions.length;
            trigger.click();
            trigger.click();
            assert(trigger.disabled && trigger.querySelector('.form-submit-spinner'), 'Delete trigger not locked');
            assert(window.nativeSubmissions.length === before + 1, 'Duplicate delete submitted');
            assert(!new FormData(form).has('not_a_submitter'), 'Programmatic submit changed request data');
            window.RestaurantFormSubmit.fail(form);
        });

        await test('Public contact: network/422/500 errors allow retry; success remains disabled', async () => {
            const form = document.querySelector('.contact-form:not(.demo-request-form)');
            const button = form.querySelector('button');
            for (const status of ['network', 422, 500]) {
                const count = window.requests.length;
                form.requestSubmit();
                submit(form);
                assert(window.requests.length === count + 1 && button.disabled, 'Duplicate contact request');
                const request = window.requests.at(-1);
                if (status === 'network') request.reject(new TypeError('Offline'));
                else respond(request, status, { errors: { email: ['Invalid email'] }, message: 'Error' });
                await until(() => !button.disabled);
                assert(!button.disabled && !form.dataset.submitting, 'Failure did not unlock');
            }
            form.requestSubmit();
            respond(window.requests.at(-1), 200, { message: 'Sent' });
            await until(() => button.textContent === 'Enquiry sent');
            assert(button.disabled && button.textContent === 'Enquiry sent' && !button.querySelector('.form-submit-spinner'), 'Success not completed/locked');
            const count = window.requests.length;
            submit(form);
            assert(window.requests.length === count, 'Successful form resubmitted');
        });

        await test('Demo request modal uses the same lock and stays disabled on success', async () => {
            const form = document.querySelector('.demo-request-form');
            form.requestSubmit();
            const count = window.requests.length;
            submit(form);
            assert(window.requests.length === count && form.querySelector('.form-submit-spinner'), 'Duplicate demo request or missing spinner');
            respond(window.requests.at(-1), 200, {});
            await until(() => form.querySelector('button').textContent === 'Demo requested');
            assert(form.querySelector('button').disabled && form.querySelector('button').textContent === 'Demo requested', 'Demo request re-enabled');
        });

        await test('AJAX regions: one request, HTTP/network failure recovery and successful replacement', async () => {
            let region = document.querySelector('[data-async-region="test"]');
            const form = region.querySelector('form');
            for (const error of ['network', 500, 422]) {
                const count = window.requests.length;
                form.requestSubmit();
                submit(form);
                assert(window.requests.length === count + 1 && form.querySelector('button').disabled, 'Region submitted twice');
                if (error === 'network') window.requests.at(-1).reject(new TypeError('Offline'));
                else respond(window.requests.at(-1), error, {});
                await until(() => !form.querySelector('button').disabled);
                assert(!form.querySelector('button').disabled, 'Region failure locked the form');
            }
            form.requestSubmit();
            window.requests.at(-1).resolve(new Response('<title>Updated</title><div data-async-region="test" data-async-mutations="true"><p>Saved</p><form method="POST" action="/region"><button>New record</button></form></div>', { status: 200 }));
            await until(() => !form.isConnected);
            region = document.querySelector('[data-async-region="test"]');
            assert(region.textContent.includes('Saved') && !form.isConnected && form.querySelector('button').disabled, 'Region replacement failed');
            region.querySelector('form').requestSubmit();
            assert(region.querySelector('button').disabled, 'Dynamic replacement not guarded');
            window.requests.at(-1).reject(new TypeError('Offline'));
            await until(() => !region.querySelector('button').disabled);
        });

        await test('History restoration resets stale locks; ordinary pageshow does not', async () => {
            const form = makeForm();
            intercept(form);
            form.requestSubmit();
            window.dispatchEvent(new PageTransitionEvent('pageshow', { persisted: false }));
            assert(form.querySelector('button').disabled, 'Ordinary pageshow unlocked submission');
            window.dispatchEvent(new PageTransitionEvent('pageshow', { persisted: true }));
            assert(!form.querySelector('button').disabled, 'History restoration left a stale lock');
        });

        await test('Demo restriction cancellation and demo contact error/success integration', async () => {
            (0, eval)(<?= json_encode($demoScript, JSON_HEX_TAG) ?>);
            const blocked = makeForm('<button>Update</button>');
            blocked.method = 'POST';
            blocked.action = '/blocked';
            submit(blocked);
            await until(() => !blocked.querySelector('button').disabled);
            assert(!blocked.querySelector('button').disabled, 'Demo-blocked form remained locked');
            const form = document.querySelector('.demo-contact-form');
            form.requestSubmit();
            const count = window.requests.length;
            submit(form);
            assert(window.requests.length === count && form.querySelector('.form-submit-spinner'), 'Duplicate demo contact request');
            respond(window.requests.at(-1), 422, { errors: { email: ['Invalid email'] } });
            await until(() => !form.querySelector('button').disabled);
            assert(!form.querySelector('button').disabled, 'Demo contact validation did not unlock');
            form.requestSubmit();
            respond(window.requests.at(-1), 200, {});
            await until(() => form.querySelector('button').textContent === 'Request sent');
            assert(form.querySelector('button').disabled && form.querySelector('button').textContent === 'Request sent', 'Demo contact success unlocked');
        });

        document.getElementById('results').textContent = JSON.stringify(results, null, 2);
        return { passed: results.filter((result) => result.passed).length, total: results.length, results };
    };
</script>
<script src="auto-filters.js"></script>
</body>
</html>
