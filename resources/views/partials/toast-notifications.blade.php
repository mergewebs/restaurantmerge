<style>
    .accent-toast-stack {
        position: fixed;
        top: max(1rem, calc(env(safe-area-inset-top) + .75rem));
        right: max(1rem, env(safe-area-inset-right));
        z-index: 9999;
        display: flex;
        flex-direction: column;
        gap: .65rem;
        width: min(25rem, calc(100vw - 2rem));
        max-height: calc(100dvh - 2rem);
        overflow-y: auto;
        overscroll-behavior: contain;
        pointer-events: none;
        font-family: 'Outfit', ui-sans-serif, system-ui, sans-serif;
    }
    .accent-toast {
        --toast-accent: #476b99;
        --toast-tint: #edf3fb;
        position: relative;
        flex-shrink: 0;
        display: grid;
        grid-template-columns: 2.25rem minmax(0, 1fr) 2rem;
        align-items: start;
        gap: .75rem;
        overflow: hidden;
        padding: .95rem .75rem 1rem .95rem;
        border: 1px solid #e8dfd5;
        border-inline-start: 3px solid var(--toast-accent);
        border-radius: 1rem;
        background: #fffdf9;
        color: #382b23;
        box-shadow: 0 12px 36px rgba(47, 31, 21, .14), 0 2px 6px rgba(47, 31, 21, .05);
        pointer-events: auto;
        animation: accentToastIn .24s ease-out both;
    }
    .accent-toast-success { --toast-accent: #28734f; --toast-tint: #eaf6ee; }
    .accent-toast-error { --toast-accent: #b5483d; --toast-tint: #fff0ec; }
    .accent-toast-warning { --toast-accent: #946317; --toast-tint: #fff5dd; }
    .accent-toast[data-state="closing"] { animation: accentToastOut .2s ease-in both; }
    .accent-toast-icon {
        display: grid;
        width: 2.25rem;
        height: 2.25rem;
        place-items: center;
        border-radius: .75rem;
        background: var(--toast-tint);
        color: var(--toast-accent);
    }
    .accent-toast-icon svg { width: 1.2rem; height: 1.2rem; }
    .accent-toast-body { min-width: 0; padding-top: .05rem; }
    .accent-toast-title { display: block; margin-bottom: .2rem; font-size: .875rem; font-weight: 800; line-height: 1.35; }
    .accent-toast-text { display: block; color: #756458; font-size: .875rem; font-weight: 500; line-height: 1.5; overflow-wrap: anywhere; white-space: pre-line; }
    .accent-toast-close {
        display: grid;
        width: 2rem;
        height: 2rem;
        padding: .45rem;
        place-items: center;
        border: 0;
        border-radius: .6rem;
        background: transparent;
        color: #8e7b6d;
        cursor: pointer;
        transition: background .15s, color .15s;
    }
    .accent-toast-close svg { width: 1rem; height: 1rem; }
    .accent-toast-close:hover { background: #f2eae0; color: #382b23; }
    .accent-toast-close:focus-visible { outline: 2px solid var(--toast-accent); outline-offset: 2px; }
    .accent-toast-progress {
        position: absolute;
        inset: auto 0 0;
        height: 3px;
        background: var(--toast-accent);
        opacity: .45;
        transform-origin: left;
        animation: accentToastProgress linear forwards;
    }
    @keyframes accentToastIn { from { opacity: 0; transform: translateY(-.6rem); } to { opacity: 1; transform: translateY(0); } }
    @keyframes accentToastOut { to { opacity: 0; transform: translateX(.75rem); } }
    @keyframes accentToastProgress { from { transform: scaleX(1); } to { transform: scaleX(0); } }
    @media (max-width: 640px) {
        .accent-toast-stack { right: .75rem; width: calc(100vw - 1.5rem); }
        .accent-toast { grid-template-columns: 2.25rem minmax(0, 1fr) 2.75rem; gap: .55rem; padding: .8rem .55rem .85rem .75rem; }
        .accent-toast-close { width: 2.75rem; height: 2.75rem; margin-top: -.25rem; }
    }
    @media (prefers-reduced-motion: reduce) {
        .accent-toast, .accent-toast-progress { animation: none !important; }
        .accent-toast-close { transition: none; }
    }
    @media print { .accent-toast-stack { display: none !important; } }
</style>
<script>
    (() => {
        if (window.RestaurantNotifications) return;

        const icons = {
            success: '<path d="m5 12 4 4L19 6"/>',
            error: '<circle cx="12" cy="12" r="9"/><path d="m9 9 6 6m0-6-6 6"/>',
            warning: '<path d="m12 3 10 18H2L12 3Zm0 6v4m0 4h.01"/>',
            info: '<circle cx="12" cy="12" r="9"/><path d="M12 11v6m0-10h.01"/>',
        };
        const titles = { success: 'Success', error: 'Please check', warning: 'Attention', info: 'Information' };
        const defaults = { success: 6000, error: 8000, warning: 8000, info: 6000 };
        const messages = new Map();
        const visible = new Set();
        const queue = [];

        const getStack = () => {
            let stack = document.querySelector('.accent-toast-stack');
            if (!stack && document.body) {
                stack = document.createElement('section');
                stack.className = 'accent-toast-stack';
                stack.setAttribute('aria-label', 'Notifications');
                document.body.append(stack);
            }
            return stack;
        };
        const arm = (state) => {
            clearTimeout(state.timer);
            if (state.closing || state.pauses.size) return;
            state.started = performance.now();
            state.timer = setTimeout(() => dismiss(state), state.remaining);
        };
        const pause = (state, reason) => {
            if (!state.pauses.size) state.remaining = Math.max(0, state.remaining - (performance.now() - state.started));
            state.pauses.add(reason);
            clearTimeout(state.timer);
            state.progress.style.animationPlayState = 'paused';
        };
        const resume = (state, reason) => {
            state.pauses.delete(reason);
            state.progress.style.animationPlayState = state.pauses.size ? 'paused' : 'running';
            arm(state);
        };
        const dismiss = (state, manual = false) => {
            if (state.closing) return;
            state.closing = true;
            clearTimeout(state.timer);
            messages.delete(state.key);
            state.toast.dataset.state = 'closing';
            if (manual && state.toast.contains(document.activeElement) && state.returnFocus?.isConnected) {
                state.returnFocus.focus({ preventScroll: true });
            }
            setTimeout(() => {
                state.toast.remove();
                visible.delete(state);
                pump();
            }, 200);
        };
        const mount = (state, stack) => {
            const toast = document.createElement('div');
            toast.className = `accent-toast accent-toast-${state.level}`;
            toast.setAttribute('role', ['error', 'warning'].includes(state.level) ? 'alert' : 'status');
            toast.setAttribute('aria-atomic', 'true');
            toast.innerHTML = `<span class="accent-toast-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">${icons[state.level]}</svg></span><div class="accent-toast-body"><span class="accent-toast-title"></span><span class="accent-toast-text"></span></div><button type="button" class="accent-toast-close" aria-label="Dismiss notification"><svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="m6 6 8 8m0-8-8 8"/></svg></button><span class="accent-toast-progress" aria-hidden="true"></span>`;
            toast.querySelector('.accent-toast-title').textContent = state.title;
            toast.querySelector('.accent-toast-text').textContent = state.message;
            state.toast = toast;
            state.progress = toast.querySelector('.accent-toast-progress');
            state.progress.style.animationDuration = `${state.duration}ms`;
            state.returnFocus = document.activeElement;
            toast.querySelector('button').addEventListener('click', () => dismiss(state, true));
            toast.addEventListener('mouseenter', () => pause(state, 'hover'));
            toast.addEventListener('mouseleave', () => resume(state, 'hover'));
            toast.addEventListener('focusin', () => pause(state, 'focus'));
            toast.addEventListener('focusout', (event) => { if (!toast.contains(event.relatedTarget)) resume(state, 'focus'); });
            stack.append(toast);
            visible.add(state);
            state.started = performance.now();
            if (document.hidden) pause(state, 'hidden');
            arm(state);
        };
        const pump = () => {
            const stack = getStack();
            if (!stack) return;
            while (visible.size < 3 && queue.length) mount(queue.shift(), stack);
        };
        const show = (message, level = 'info', options = {}) => {
            if (!['string', 'number'].includes(typeof message) || !String(message).trim()) return;
            const text = String(message).trim();
            const normalized = Object.hasOwn(titles, level) ? level : 'info';
            const title = String(options?.title || titles[normalized]);
            const requested = Number(options?.duration);
            const duration = Number.isFinite(requested) && requested > 0 ? Math.min(30000, Math.max(1000, requested)) : defaults[normalized];
            const key = `${normalized}:${title}:${text}`;
            const existing = messages.get(key);
            if (existing) {
                existing.duration = duration;
                existing.remaining = duration;
                if (existing.toast) {
                    existing.progress.style.animationDuration = `${duration}ms`;
                    existing.progress.getAnimations?.().forEach((animation) => { animation.currentTime = 0; });
                    arm(existing);
                }
                return existing.toast;
            }
            const state = { key, message: text, title, level: normalized, duration, remaining: duration, pauses: new Set(), closing: false };
            messages.set(key, state);
            queue.push(state);
            pump();
            return state.toast;
        };
        const consumeFeedback = (root = document) => {
            const selector = '[data-async-feedback], [data-page-feedback]';
            const feedback = [...root.querySelectorAll(selector)];
            if (root.matches?.(selector)) feedback.unshift(root);
            feedback.forEach((element) => {
                if (element.dataset.feedbackConsumed === 'true') return;
                element.dataset.feedbackConsumed = 'true';
                element.hidden = true;
                show(element.textContent, element.dataset.feedbackLevel || 'info', { duration: element.dataset.feedbackDuration });
            });
        };

        // Replace native validation popovers only for a user's actual submit attempt.
        // Background filter checkValidity() calls must not generate notifications.
        const attempts = new Set();
        const invalidForms = new WeakMap();
        const expectValidation = (form) => {
            if (!(form instanceof HTMLFormElement)) return;
            attempts.add(form);
            setTimeout(() => attempts.delete(form), 0);
        };
        document.addEventListener('click', (event) => {
            const control = event.target instanceof Element ? event.target.closest('button, input[type="submit"], input[type="image"]') : null;
            if (control && ['submit', 'image'].includes(control.type)) expectValidation(control.form);
        }, true);
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Enter' && event.target.tagName !== 'TEXTAREA' && !event.target.isContentEditable) expectValidation(event.target.form);
        }, true);
        document.addEventListener('invalid', (event) => {
            const field = event.target;
            if (!attempts.has(field.form)) return;
            event.preventDefault();
            field.setAttribute('aria-invalid', 'true');
            field.dataset.clientInvalid = 'true';
            if (!invalidForms.has(field.form)) {
                const summary = { first: field, messages: new Set() };
                invalidForms.set(field.form, summary);
                setTimeout(() => {
                    invalidForms.delete(field.form);
                    const details = [...summary.messages];
                    const message = details.slice(0, 5).join('\n') + (details.length > 5 ? `\n${details.length - 5} more fields need attention.` : '');
                    show(message, 'error', { title: 'Please review the form' });
                    summary.first.focus();
                }, 0);
            }
            const label = (field.labels?.[0]?.textContent || field.getAttribute('aria-label') || field.name || 'This field').replace(/\s+/g, ' ').trim();
            invalidForms.get(field.form).messages.add(`${label}: ${field.validationMessage}`);
        }, true);
        document.addEventListener('input', (event) => {
            const field = event.target;
            if (field.dataset?.clientInvalid === 'true' && field.validity?.valid) {
                field.removeAttribute('aria-invalid');
                delete field.dataset.clientInvalid;
            }
        });

        document.addEventListener('visibilitychange', () => visible.forEach((state) => document.hidden ? pause(state, 'hidden') : resume(state, 'hidden')));
        window.addEventListener('pagehide', () => {
            visible.forEach((state) => { clearTimeout(state.timer); state.toast.remove(); });
            visible.clear();
            messages.clear();
            queue.length = 0;
        });
        window.RestaurantNotifications = { show, consumeFeedback };
        const initialize = () => { consumeFeedback(document); pump(); };
        if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', initialize);
        else initialize();
    })();
</script>
