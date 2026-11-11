<script>
    (() => {
        if (window.RestaurantFilters) return;

        const states = new WeakMap();
        const selector = 'form[data-auto-submit-form]';
        const signature = (form) => new URLSearchParams([...new FormData(form)].filter(([, value]) => typeof value === 'string')).toString();
        const stateFor = (form) => {
            if (!states.has(form)) states.set(form, { timer: null, applied: signature(form), composing: new Set() });
            return states.get(form);
        };
        const isFilter = (form) => form instanceof HTMLFormElement && form.matches(selector) && form.method.toUpperCase() === 'GET';
        const togglePanels = (field) => {
            const target = field.dataset.rangeToggleTarget;
            if (!target) return;
            document.querySelectorAll('[data-range-toggle-panel]').forEach((panel) => {
                if (panel.dataset.rangeTogglePanel === target) {
                    panel.classList.toggle('hidden', field.value !== (field.dataset.rangeToggleValue || 'custom'));
                }
            });
        };
        const initialize = (root = document) => {
            const forms = [...root.querySelectorAll(selector)];
            if (root.matches?.(selector)) forms.push(root);
            forms.filter(isFilter).forEach((form) => {
                stateFor(form);
                form.querySelectorAll('[data-range-toggle-target]').forEach(togglePanels);
            });
        };
        const clearTimer = (form) => {
            const state = stateFor(form);
            clearTimeout(state.timer);
            state.timer = null;
        };
        const syncFilters = (field, form) => {
            const region = form.closest('[data-async-region]');
            const forms = region ? region.querySelectorAll(selector) : [form];
            forms.forEach((related) => {
                clearTimer(related);
                // Finance has separate range/search forms carrying each other's hidden values.
                related.querySelectorAll('input[type="hidden"]').forEach((hidden) => {
                    if (hidden.name === field.name) hidden.value = field.value;
                });
            });
        };
        const invalidate = (form) => {
            const region = form.closest('[data-async-region]');
            const request = region?._asyncRequest;
            // Only read requests can be cancelled/replaced. Never interrupt a mutation.
            if (request?.method === 'GET') {
                request.controller.abort();
                window.RestaurantFormSubmit?.releaseFilter(request.form);
                region._asyncRequest = null;
                delete region.dataset.asyncMethod;
                region.classList.remove('is-async-loading');
            }
        };
        const ready = (form) => {
            if (!form.isConnected || stateFor(form).composing.size || !form.checkValidity()) return false;
            return [...form.querySelectorAll('[data-range-toggle-panel], [data-filter-complete-range]')].every((panel) => {
                if (panel.classList.contains('hidden')) return true;
                if (panel.dataset.filterCompleteRange && form.elements.namedItem('mode')?.value !== panel.dataset.filterCompleteRange) return true;
                const dates = [...panel.querySelectorAll('input[type="date"]')];
                return dates.every((field) => field.value && field.validity.valid) &&
                    (dates.length < 2 || dates[0].value <= dates[1].value);
            });
        };
        const submit = (form) => {
            if (!isFilter(form)) return;
            clearTimer(form);
            if (!ready(form) || signature(form) === stateFor(form).applied) return;
            // requestSubmit preserves HTML validation and the shared duplicate-submit guard.
            form.requestSubmit();
        };
        const searchField = (field) => field instanceof HTMLInputElement &&
            (field.hasAttribute('data-auto-submit-search') || ['text', 'search', 'email', 'tel', 'url', 'number'].includes(field.type));
        const getForm = (field) => {
            const form = field?.form;
            return isFilter(form) && field.name && !field.disabled && field.type !== 'hidden' ? form : null;
        };
        const schedule = (field, form) => {
            clearTimer(form);
            const delay = Number(field.dataset.autoSubmitDelay || form.dataset.autoSubmitDelay || 700);
            stateFor(form).timer = setTimeout(() => submit(form), Number.isFinite(delay) ? Math.min(800, Math.max(500, delay)) : 700);
        };

        document.addEventListener('input', (event) => {
            const field = event.target;
            const form = getForm(field);
            if (!form || !searchField(field)) return;
            syncFilters(field, form);
            invalidate(form); // Prevent an older response overwriting text still being typed.
            clearTimer(form);
            if (!event.isComposing && !stateFor(form).composing.has(field)) schedule(field, form);
        });
        document.addEventListener('compositionstart', (event) => {
            const form = getForm(event.target);
            if (!form || !searchField(event.target)) return;
            syncFilters(event.target, form);
            stateFor(form).composing.add(event.target);
            clearTimer(form);
            invalidate(form);
        });
        document.addEventListener('compositionend', (event) => {
            const form = getForm(event.target);
            if (!form || !searchField(event.target)) return;
            syncFilters(event.target, form);
            stateFor(form).composing.delete(event.target);
            schedule(event.target, form);
        });
        document.addEventListener('change', (event) => {
            const field = event.target;
            const form = getForm(field);
            if (!form || searchField(field)) return;
            syncFilters(field, form);
            invalidate(form);
            togglePanels(field);
            if (field.dataset.autoSubmitSkipOn === field.value) return;
            submit(form);
        });
        document.addEventListener('submit', (event) => {
            if (isFilter(event.target)) clearTimer(event.target);
        }, true);
        window.addEventListener('pagehide', () => {
            document.querySelectorAll(selector).forEach((form) => {
                clearTimer(form);
                stateFor(form).composing.clear();
            });
        });

        window.RestaurantFilters = { initialize, submit };
        if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', () => initialize());
        else initialize();
    })();
</script>
