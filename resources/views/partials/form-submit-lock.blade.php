<style>
    form[data-submitting="true"] button[type="submit"],
    form[data-submitting="true"] input[type="submit"],
    button[data-submit-loading="true"],
    input[data-submit-loading="true"] {
        cursor: wait !important;
    }

    button[data-submit-loading="true"],
    input[data-submit-loading="true"] {
        opacity: 0.72;
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

        const normalizeLabel = (value) => (value || '').replace(/\s+/g, ' ').trim().toLowerCase();

        const getControlLabel = (control) => {
            if (!control) {
                return '';
            }

            return control.tagName === 'INPUT' ? control.value : control.textContent;
        };

        const setControlLabel = (control, label) => {
            if (!control) {
                return;
            }

            if (control.tagName === 'INPUT') {
                control.value = label;
                return;
            }

            control.textContent = label;
        };

        const getAssociatedSubmitControls = (form) => {
            const controls = new Set(form.querySelectorAll('button[type="submit"], input[type="submit"]'));

            if (form.id && window.CSS && typeof window.CSS.escape === 'function') {
                document
                    .querySelectorAll(`button[form="${window.CSS.escape(form.id)}"][type="submit"], input[form="${window.CSS.escape(form.id)}"][type="submit"]`)
                    .forEach((control) => controls.add(control));
            }

            return [...controls];
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
            if (!(form instanceof HTMLFormElement)) {
                return;
            }

            delete form.dataset.submitting;
            form.removeAttribute('aria-busy');

            getAssociatedSubmitControls(form).forEach((control) => {
                if (control.dataset.originalLabel) {
                    setControlLabel(control, control.dataset.originalLabel);
                }

                control.disabled = false;
                control.removeAttribute('aria-disabled');
                delete control.dataset.submitLoading;
            });
        };

        const lockForm = (form, submitter = null) => {
            if (!(form instanceof HTMLFormElement) || form.dataset.submitting === 'true') {
                return false;
            }

            form.dataset.submitting = 'true';
            form.setAttribute('aria-busy', 'true');

            getAssociatedSubmitControls(form).forEach((control) => {
                if (!control.dataset.originalLabel) {
                    control.dataset.originalLabel = getControlLabel(control);
                }

                setControlLabel(control, inferLoadingText(form, control === submitter ? submitter : control));
                control.disabled = true;
                control.dataset.submitLoading = 'true';
                control.setAttribute('aria-disabled', 'true');
            });

            return true;
        };

        document.addEventListener('submit', (event) => {
            const form = event.target;

            if (!(form instanceof HTMLFormElement) || event.defaultPrevented) {
                return;
            }

            if (form.dataset.submitting === 'true') {
                event.preventDefault();
                return;
            }

            lockForm(form, event.submitter instanceof HTMLElement ? event.submitter : null);
        });

        HTMLFormElement.prototype.submit = function submitWithLock() {
            if (this.dataset.submitting === 'true') {
                return;
            }

            lockForm(this);
            nativeSubmit.call(this);
        };

        if (typeof nativeRequestSubmit === 'function') {
            HTMLFormElement.prototype.requestSubmit = function requestSubmitWithLock(submitter) {
                if (this.dataset.submitting === 'true') {
                    return;
                }

                nativeRequestSubmit.call(this, submitter);
            };
        }

        const resetSubmittingForms = () => {
            document.querySelectorAll('form[data-submitting="true"]').forEach((form) => unlockForm(form));
        };

        window.addEventListener('pageshow', resetSubmittingForms);
    })();
</script>
