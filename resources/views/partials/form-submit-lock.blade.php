<style>
    [data-submit-loading="true"] {
        cursor: wait !important;
        opacity: 0.72;
    }

    .form-submit-spinner {
        display: inline-block !important;
        width: 1em !important;
        height: 1em !important;
        flex-shrink: 0;
        margin-inline-end: 0.4em;
        vertical-align: -0.125em;
        border: 2px solid currentColor !important;
        border-right-color: transparent !important;
        border-radius: 50% !important;
        animation: formSubmitSpin 0.75s linear infinite !important;
    }

    @keyframes formSubmitSpin {
        to { transform: rotate(360deg); }
    }

    @media (prefers-reduced-motion: reduce) {
        .form-submit-spinner { animation-duration: 1.5s !important; }
    }
</style>

<script>
    (() => {
        if (window.__accentFormSubmitLockInitialized) {
            return;
        }

        window.__accentFormSubmitLockInitialized = true;

        const nativeSubmit = HTMLFormElement.prototype.submit;
        const nativeRequestSubmit = HTMLFormElement.prototype.requestSubmit;
        const submissions = new WeakMap();
        let clickedControl = null;

        // Safari does not always focus tapped buttons used to submit hidden delete forms.
        document.addEventListener('click', (event) => {
            const control = event.target instanceof Element ? event.target.closest('button') : null;
            clickedControl = control;
            setTimeout(() => {
                if (clickedControl === control) clickedControl = null;
            }, 0);
        }, true);

        const normalizeLabel = (value) => (value || '').replace(/\s+/g, ' ').trim().toLowerCase();

        const getControlLabel = (control) => {
            if (!control) {
                return '';
            }

            return control.tagName === 'INPUT' ? control.value : control.textContent;
        };

        const getAssociatedSubmitControls = (form) => {
            // form.elements includes default-type buttons and external form="..." controls.
            return [...form.elements].filter((control) =>
                (control instanceof HTMLButtonElement && control.type === 'submit') ||
                (control instanceof HTMLInputElement && ['submit', 'image'].includes(control.type))
            );
        };

        const inferLoadingText = (form, control) => {
            const explicitText = control?.dataset.loadingText || form.dataset.loadingText;

            if (explicitText) {
                return explicitText;
            }

            const label = normalizeLabel(getControlLabel(control));
            const action = normalizeLabel(form.getAttribute('action'));

            if (/\blog(?:\s|-)?in\b|\bsign(?:\s|-)?in\b/.test(label)) {
                return 'Logging in...';
            }

            if (/\bplace\b.*\border\b|\border\b/.test(label) || action.includes('/order')) {
                return 'Placing order...';
            }

            if (/\b(save|update|create|add)\b/.test(label)) {
                return 'Saving...';
            }

            if (/\b(submit|send|verify|register|reset|reserve|book)\b/.test(label)) {
                return 'Submitting...';
            }

            return 'Processing...';
        };

        const unlockForm = (form) => {
            const state = submissions.get(form);
            if (!state) {
                return;
            }

            submissions.delete(form);
            delete form.dataset.submitting;
            if (state.busy === null) form.removeAttribute('aria-busy');
            else form.setAttribute('aria-busy', state.busy);
            state.proxy?.remove();

            state.controls.forEach(({ control, nodes, value, ariaDisabled, spinner }) => {
                if (nodes) control.replaceChildren(...nodes);
                else control.value = value;
                spinner.remove();
                control.disabled = false; // Pre-disabled controls were never changed.
                if (ariaDisabled === null) control.removeAttribute('aria-disabled');
                else control.setAttribute('aria-disabled', ariaDisabled);
                delete control.dataset.submitLoading;
            });
        };

        const lockForm = (form, submitter = null) => {
            if (!(form instanceof HTMLFormElement) || submissions.has(form)) {
                return false;
            }

            const state = { controls: [], busy: form.getAttribute('aria-busy'), handled: false, proxy: null };
            submissions.set(form, state);
            form.dataset.submitting = 'true';
            form.setAttribute('aria-busy', 'true');

            // Disabling a named submitter must not drop its value from the request.
            if (submitter?.name && !submitter.disabled && submitter.type === 'submit') {
                state.proxy = document.createElement('input');
                state.proxy.type = 'hidden';
                state.proxy.name = submitter.name;
                state.proxy.value = submitter.value;
                form.append(state.proxy);
            }

            const controls = new Set(getAssociatedSubmitControls(form));
            if (submitter) controls.add(submitter);
            controls.forEach((control) => {
                if (control.disabled) return;
                const spinner = document.createElement('span');
                spinner.className = 'form-submit-spinner';
                spinner.setAttribute('aria-hidden', 'true');
                const label = inferLoadingText(form, control);
                const nodes = control instanceof HTMLButtonElement ? [...control.childNodes] : null;
                state.controls.push({ control, nodes, value: control.value, ariaDisabled: control.getAttribute('aria-disabled'), spinner });
                if (nodes) {
                    const text = document.createElement('span');
                    text.textContent = label;
                    control.replaceChildren(spinner, text);
                } else {
                    control.value = label;
                    control.after(spinner);
                }
                control.disabled = true;
                control.dataset.submitLoading = 'true';
                control.setAttribute('aria-disabled', 'true');
            });

            return true;
        };

        // AJAX handlers claim the captured submission, then report failure or success.
        window.RestaurantFormSubmit = {
            handle(form) {
                const state = submissions.get(form);
                if (!state || state.handled) return false;
                state.handled = true;
                return true;
            },
            fail: unlockForm,
            releaseFilter(form) {
                // Read-only filters remain reusable; mutation forms stay locked on success.
                if (form instanceof HTMLFormElement && form.method.toUpperCase() === 'GET' && form.hasAttribute('data-auto-submit-form')) {
                    unlockForm(form);
                }
            },
            complete(form, label = 'Submitted') {
                const state = submissions.get(form);
                if (!state) return;
                form.removeAttribute('aria-busy');
                state.controls.forEach(({ control, spinner }) => {
                    spinner.remove();
                    if (control instanceof HTMLButtonElement) control.textContent = label;
                    else control.value = label;
                    delete control.dataset.submitLoading;
                });
                // Keep the form and its buttons locked after a successful request.
            },
        };

        document.addEventListener('submit', (event) => {
            const form = event.target;

            if (!(form instanceof HTMLFormElement) || event.defaultPrevented) {
                return;
            }

            if (submissions.has(form)) {
                event.preventDefault();
                event.stopImmediatePropagation();
                return;
            }

            lockForm(form, event.submitter);
            const state = submissions.get(form);
            // A task (not a microtask) runs after native event propagation finishes.
            setTimeout(() => {
                // Cancelled confirmation, demo restrictions, or client validation: no request ran.
                if (event.defaultPrevented && !state.handled && submissions.get(form) === state) unlockForm(form);
            }, 0);
        }, true);

        HTMLFormElement.prototype.submit = function submitWithLock() {
            const active = clickedControl || document.activeElement;
            const trigger = active instanceof HTMLButtonElement && active.type === 'button' ? active : null;
            if (!lockForm(this, trigger)) return;
            try {
                nativeSubmit.call(this);
            } catch (error) {
                unlockForm(this);
                throw error;
            }
        };

        if (typeof nativeRequestSubmit === 'function') {
            HTMLFormElement.prototype.requestSubmit = function requestSubmitWithLock(submitter) {
                if (submissions.has(this)) {
                    return;
                }

                if (submitter === undefined) nativeRequestSubmit.call(this);
                else nativeRequestSubmit.call(this, submitter);
            };
        }

        const resetSubmittingForms = () => {
            document.querySelectorAll('form[data-submitting="true"]').forEach((form) => unlockForm(form));
        };

        // Restoring a page from history begins a new interaction, not a retry in flight.
        window.addEventListener('pageshow', (event) => {
            if (event.persisted) resetSubmittingForms();
        });
    })();
</script>
