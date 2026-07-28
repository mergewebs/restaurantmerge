@php
    $demoContactEmail = config('restaurant.contact_email', 'hello@example.com');
    $contactHref = 'mailto:'.$demoContactEmail.'?subject='.rawurlencode('Restaurant application enquiry');
    $accessHref = 'mailto:'.$demoContactEmail.'?subject='.rawurlencode('Request full restaurant application access');
@endphp

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
    @media (max-width: 520px) { .demo-modal-content { padding: 1.5rem; } .demo-modal-actions { grid-template-columns: 1fr; } .demo-mode-pill { bottom: 5.5rem; } }
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
            <p class="demo-modal-eyebrow">You are exploring the live demo</p>
            <h2 id="demo-modal-title" class="demo-modal-title">Ready to run your restaurant with it?</h2>
            <p class="demo-modal-copy">All screens, fields and workflows are available to explore. Saving changes is disabled to keep this shared demonstration clean for every visitor. Contact us for a private walkthrough or full access.</p>
            <div class="demo-modal-actions">
                <a class="demo-modal-action demo-modal-primary" href="{{ $contactHref }}">Contact Us</a>
                <a class="demo-modal-action demo-modal-secondary" href="{{ $accessHref }}">Request Full Access</a>
            </div>
        </div>
    </section>
</div>

<script>
    (() => {
        const modal = document.getElementById('demo-mode-modal');
        const logoutUrl = @json(route('logout'));
        const unsafeMethods = new Set(['POST', 'PUT', 'PATCH', 'DELETE']);
        const finalActionPattern = /^(save|update|delete|confirm|complete|pay|submit|cancel|checkout|refund)(\s|$)/i;
        const methodOf = (value) => String(value || 'GET').toUpperCase();
        const isLogoutUrl = (value) => {
            try { return new URL(value, window.location.href).pathname === new URL(logoutUrl, window.location.href).pathname; }
            catch (_) { return false; }
        };
        const show = () => {
            modal.classList.add('is-open');
            modal.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
            modal.querySelector('[data-demo-modal-close]')?.focus();
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
            if (!(form instanceof HTMLFormElement)) return;
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
            if (unsafeMethods.has(methodOf(this.method)) && !isLogoutUrl(action)) { show(); return; }
            return nativeSubmit.call(this);
        };

        const nativeFetch = window.fetch.bind(window);
        window.fetch = (input, options = {}) => {
            const method = methodOf(options.method || (input instanceof Request ? input.method : 'GET'));
            const url = input instanceof Request ? input.url : input;
            if (unsafeMethods.has(method) && !isLogoutUrl(url)) {
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
