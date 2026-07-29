<style>
    .demo-mode-pill { position: fixed; right: 1rem; bottom: 1rem; z-index: 90; display: inline-flex; align-items: center; gap: .55rem; border: 1px solid rgba(200,155,60,.38); border-radius: 999px; padding: .65rem .9rem; background: rgba(23,17,12,.94); color: #fff7eb; box-shadow: 0 16px 44px rgba(0,0,0,.3); font: 700 .72rem/1 'Outfit',sans-serif; letter-spacing: .08em; text-transform: uppercase; cursor: pointer; backdrop-filter: blur(16px); }
    .demo-mode-pill span { width: .55rem; height: .55rem; border-radius: 999px; background: #d9ad50; box-shadow: 0 0 0 4px rgba(217,173,80,.14); }
    .demo-modal-wrap { position: fixed; inset: 0; z-index: 1000; display: none; place-items: center; padding: 1rem; }
    .demo-modal-wrap.is-open { display: grid; }
    .demo-modal-backdrop { position: absolute; inset: 0; background: rgba(12,8,5,.78); backdrop-filter: blur(9px); }
    .demo-modal-card { position: relative; width: min(100%, 31rem); overflow: hidden; border: 1px solid rgba(224,181,88,.3); border-radius: 1.5rem; background: linear-gradient(150deg,#241a12 0%,#17110c 62%,#21170f 100%); color: #fff7eb; box-shadow: 0 35px 90px rgba(0,0,0,.55); }
    .demo-modal-card::before { content: ''; position: absolute; width: 15rem; height: 15rem; right: -7rem; top: -8rem; border-radius: 999px; background: rgba(210,165,67,.17); filter: blur(4px); }
    .demo-modal-content { position: relative; padding: 2rem; }
    .demo-modal-icon { display: grid; width: 3.2rem; height: 3.2rem; place-items: center; border-radius: 1rem; background: #d5a744; color: #17110c; box-shadow: 0 14px 30px rgba(213,167,68,.24); }
    .demo-modal-eyebrow { margin: 1.35rem 0 .4rem; color: #deb963; font-size: .68rem; font-weight: 800; letter-spacing: .16em; text-transform: uppercase; }
    .demo-modal-title { margin: 0; color: #fffaf1; font-family: 'Cormorant Garamond',serif; font-size: clamp(2rem,7vw,2.65rem); font-weight: 700; line-height: .95; }
    .demo-modal-copy { margin: 1rem 0 0; color: rgba(255,247,235,.7); font-size: .92rem; line-height: 1.65; }
    .demo-modal-actions { display: grid; grid-template-columns: 1fr 1fr; gap: .7rem; margin-top: 1.5rem; }
    .demo-modal-action { display: inline-flex; min-height: 3rem; align-items: center; justify-content: center; border-radius: .85rem; padding: .75rem 1rem; font-size: .78rem; font-weight: 800; text-decoration: none; transition: transform .18s ease,background .18s ease; }
    .demo-modal-action:hover { transform: translateY(-1px); }
    .demo-modal-primary { background: #d5a744; color: #17110c; }
    .demo-modal-secondary { border: 1px solid rgba(255,255,255,.15); background: rgba(255,255,255,.06); color: #fff7eb; }
    .demo-modal-close { position: absolute; top: 1rem; right: 1rem; display: grid; width: 2.35rem; height: 2.35rem; place-items: center; border: 1px solid rgba(255,255,255,.12); border-radius: 999px; background: rgba(255,255,255,.06); color: #fff7eb; cursor: pointer; }
    .demo-contact-form { display: grid; grid-template-columns: 1fr 1fr; gap: .8rem; margin-top: 1.4rem; }
    .demo-contact-field { display: grid; gap: .38rem; text-align: left; }
    .demo-contact-field:first-child { grid-column: 1 / -1; }
    .demo-contact-field label { color: rgba(255,247,235,.72); font-size: .64rem; font-weight: 800; letter-spacing: .1em; text-transform: uppercase; }
    .demo-contact-field input { width: 100%; min-height: 3rem; border: 1px solid rgba(255,255,255,.15); border-radius: .75rem; padding: .7rem .85rem; background: rgba(255,255,255,.07); color: #fffaf1; outline: none; }
    .demo-contact-field input::placeholder { color: rgba(255,247,235,.35); }
    .demo-contact-field input:focus { border-color: #d5a744; background: rgba(255,255,255,.1); }
    .demo-contact-field input[aria-invalid="true"] { border-color: #e98b76; }
    .demo-contact-error { color: #f3a18e; font-size: .67rem; font-weight: 700; }
    .demo-contact-submit { grid-column: 1 / -1; min-height: 3.1rem; border: 0; border-radius: .85rem; background: #d5a744; color: #17110c; font-weight: 850; cursor: pointer; }
    .demo-contact-submit:disabled { cursor: wait; opacity: .7; }
    .demo-contact-feedback { margin-top: 1.25rem; border: 1px solid rgba(125,196,139,.28); border-radius: .9rem; padding: 1rem; background: rgba(72,139,84,.13); color: #dff5e3; font-size: .82rem; line-height: 1.5; }
    .demo-contact-feedback.is-error { border-color: rgba(233,139,118,.35); background: rgba(166,67,48,.14); color: #ffd8cf; }
    .demo-contact-feedback[hidden] { display: none; }
    @media (max-width: 520px) { .demo-modal-content { padding: 1.5rem; } .demo-contact-form { grid-template-columns: 1fr; } .demo-contact-field:first-child,.demo-contact-submit { grid-column: auto; } .demo-mode-pill { bottom: 5.5rem; } }
</style>

<button type="button" class="demo-mode-pill" data-demo-modal-open aria-label="About demo mode"><span></span>Demo mode · Read only</button>

<div id="demo-mode-modal" class="demo-modal-wrap" role="dialog" aria-modal="true" aria-labelledby="demo-modal-title" aria-hidden="true">
    <div class="demo-modal-backdrop" data-demo-modal-close></div>
    <section class="demo-modal-card">
        <button type="button" class="demo-modal-close" data-demo-modal-close aria-label="Close demo message">&times;</button>
        <div class="demo-modal-content">
            <div class="demo-modal-icon" aria-hidden="true">
                <svg width="25" height="25" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4"/></svg>
            </div>
            <p class="demo-modal-eyebrow">Enjoying the live demo?</p>
            <h2 id="demo-modal-title" class="demo-modal-title">Let’s build your restaurant workspace.</h2>
            <p class="demo-modal-copy">Leave your details for a private walkthrough and full access tailored to your operation.</p>
            <form class="demo-contact-form" action="{{ route('contact-submissions.store') }}" method="POST">
                @csrf
                <div class="demo-contact-field"><label for="admin_demo_full_name">Full name</label><input id="admin_demo_full_name" name="full_name" type="text" maxlength="120" autocomplete="name" required placeholder="Your full name"></div>
                <div class="demo-contact-field"><label for="admin_demo_email">Email address</label><input id="admin_demo_email" name="email" type="email" maxlength="255" autocomplete="email" required placeholder="you@example.com"></div>
                <div class="demo-contact-field"><label for="admin_demo_telephone">Telephone</label><input id="admin_demo_telephone" name="telephone" type="tel" maxlength="30" autocomplete="tel" required placeholder="+34 600 000 000"></div>
                <button class="demo-contact-submit" type="submit">Request my demo</button>
            </form>
            <div class="demo-contact-feedback" role="status" aria-live="polite" hidden></div>
        </div>
    </section>
</div>

<script>
    (() => {
        const modal = document.getElementById('demo-mode-modal');
        const contactForm = modal.querySelector('.demo-contact-form');
        const contactFeedback = modal.querySelector('.demo-contact-feedback');
        const logoutUrl = @json(route('logout'));
        const contactUrl = @json(route('contact-submissions.store'));
        const unsafeMethods = new Set(['POST', 'PUT', 'PATCH', 'DELETE']);
        const finalActionPattern = /^(save|update|delete|confirm|complete|pay|submit|cancel|checkout|refund)(\s|$)/i;
        const methodOf = (value) => String(value || 'GET').toUpperCase();
        const isLogoutUrl = (value) => {
            try { return new URL(value, window.location.href).pathname === new URL(logoutUrl, window.location.href).pathname; }
            catch (_) { return false; }
        };
        const isContactUrl = (value) => {
            try { return new URL(value, window.location.href).pathname === new URL(contactUrl, window.location.href).pathname; }
            catch (_) { return false; }
        };
        const show = () => {
            modal.classList.add('is-open');
            modal.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
            contactForm.querySelector('input')?.focus();
        };
        const close = () => {
            modal.classList.remove('is-open');
            modal.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
        };

        window.DemoMode = { show, close };
        document.querySelectorAll('[data-demo-modal-open]').forEach((button) => button.addEventListener('click', show));
        modal.querySelectorAll('[data-demo-modal-close]').forEach((button) => button.addEventListener('click', close));
        document.addEventListener('keydown', (event) => { if (event.key === 'Escape') close(); });

        contactForm.addEventListener('submit', async (event) => {
            event.preventDefault();
            contactForm.querySelectorAll('.demo-contact-error').forEach((error) => error.remove());
            contactForm.querySelectorAll('[aria-invalid="true"]').forEach((input) => input.removeAttribute('aria-invalid'));
            contactFeedback.hidden = true;

            const submit = contactForm.querySelector('[type="submit"]');
            const originalLabel = submit.textContent;
            submit.disabled = true;
            submit.textContent = 'Sending…';

            try {
                const response = await fetch(contactForm.action, {
                    method: 'POST',
                    body: new FormData(contactForm),
                    headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                });
                const data = await response.json().catch(() => ({}));

                if (! response.ok) {
                    if (response.status === 422 && data.errors) {
                        Object.entries(data.errors).forEach(([name, messages]) => {
                            const input = contactForm.elements.namedItem(name);
                            if (! input) return;
                            input.setAttribute('aria-invalid', 'true');
                            const error = document.createElement('span');
                            error.className = 'demo-contact-error';
                            error.textContent = messages[0];
                            input.after(error);
                        });
                        throw new Error('Please check the highlighted details.');
                    }
                    throw new Error(data.message || 'Unable to send your request right now.');
                }

                contactForm.reset();
                contactFeedback.classList.remove('is-error');
                contactFeedback.textContent = data.message || 'Thanks! We’ll contact you shortly.';
                contactFeedback.hidden = false;
            } catch (error) {
                contactFeedback.classList.add('is-error');
                contactFeedback.textContent = error.message || 'Unable to send your request right now.';
                contactFeedback.hidden = false;
            } finally {
                submit.disabled = false;
                submit.textContent = originalLabel;
            }
        });

        document.addEventListener('click', (event) => {
            const action = event.target.closest('button, [role="button"]');
            const form = action?.closest('form');
            if (!action || modal.contains(action) || (form && isLogoutUrl(form.action))) return;

            const label = (action.dataset.demoAction || action.textContent || '').trim();
            if (action.dataset.demoFinalAction !== undefined || finalActionPattern.test(label)) {
                event.preventDefault();
                event.stopImmediatePropagation();
                show();
            }
        }, true);

        document.addEventListener('submit', (event) => {
            const form = event.target;
            if (!(form instanceof HTMLFormElement) || modal.contains(form)) return;
            const action = form.getAttribute('action') || window.location.href;
            if (unsafeMethods.has(methodOf(form.method)) && !isLogoutUrl(action)) {
                event.preventDefault();
                event.stopImmediatePropagation();
                show();
            }
        }, true);

        const nativeSubmit = HTMLFormElement.prototype.submit;
        HTMLFormElement.prototype.submit = function () {
            const action = this.getAttribute('action') || window.location.href;
            if (unsafeMethods.has(methodOf(this.method)) && !isLogoutUrl(action) && !isContactUrl(action)) { show(); return; }
            return nativeSubmit.call(this);
        };

        const nativeFetch = window.fetch.bind(window);
        window.fetch = (input, options = {}) => {
            const method = methodOf(options.method || (input instanceof Request ? input.method : 'GET'));
            const url = input instanceof Request ? input.url : input;
            if (unsafeMethods.has(method) && !isLogoutUrl(url) && !isContactUrl(url)) {
                show();
                return Promise.resolve(new Response(JSON.stringify({ message: 'Action disabled in demo mode', demo_mode: true }), {
                    status: 423,
                    headers: { 'Content-Type': 'application/json' },
                }));
            }
            return nativeFetch(input, options);
        };

        @if(session('demo_action_blocked'))
            show();
        @endif
    })();
</script>
