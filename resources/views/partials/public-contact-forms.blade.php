<script>
    (() => {
        const modal = document.getElementById('demo-request');
        const panel = modal?.querySelector('.demo-request-panel');
        let returnFocus = null;

        const close = () => {
            modal?.classList.remove('is-open');
            modal?.setAttribute('aria-hidden', 'true');
            document.body.classList.remove('demo-modal-open');
            returnFocus?.focus();
        };

        const showNotice = (error, text, demo) => {
            window.RestaurantNotifications.show(text, error ? 'error' : 'success', {
                title: error ? 'Could not send enquiry' : (demo ? 'Demo requested' : 'Enquiry sent'),
            });
        };

        document.querySelectorAll('a[href="#demo-request"]').forEach((link) => {
            link.addEventListener('click', (event) => {
                if (!modal) return;
                event.preventDefault();
                returnFocus = link;
                modal.classList.add('is-open');
                modal.setAttribute('aria-hidden', 'false');
                document.body.classList.add('demo-modal-open');
                requestAnimationFrame(() => modal.querySelector('input')?.focus());
            });
        });
        modal?.querySelectorAll('[data-demo-modal-close]').forEach((control) => control.addEventListener('click', close));
        document.addEventListener('keydown', (event) => {
            if (!modal?.classList.contains('is-open')) return;
            if (event.key === 'Escape') close();
            if (event.key !== 'Tab') return;
            const focusable = [...panel.querySelectorAll('button,input')].filter((element) => !element.disabled);
            const first = focusable[0];
            const last = focusable[focusable.length - 1];
            if (event.shiftKey && document.activeElement === first) {
                event.preventDefault();
                last?.focus();
            } else if (!event.shiftKey && document.activeElement === last) {
                event.preventDefault();
                first?.focus();
            }
        });

        document.querySelectorAll('.contact-form').forEach((form) => {
            form.addEventListener('submit', async (event) => {
                event.preventDefault();
                if (!window.RestaurantFormSubmit.handle(form)) return;
                const demo = form.classList.contains('demo-request-form');
                form.querySelectorAll('.contact-error').forEach((error) => error.remove());
                form.querySelectorAll('[aria-invalid="true"]').forEach((input) => input.removeAttribute('aria-invalid'));

                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        body: new FormData(form),
                        headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                    });
                    const data = await response.json().catch(() => ({}));
                    if (!response.ok) {
                        if (response.status === 422 && data.errors) {
                            Object.entries(data.errors).forEach(([name, messages]) => {
                                const input = form.elements.namedItem(name);
                                if (!input) return;
                                input.setAttribute('aria-invalid', 'true');
                                const error = document.createElement('span');
                                error.className = 'contact-error';
                                error.textContent = messages[0];
                                input.after(error);
                            });
                            throw new Error('Please check the highlighted details and try again.');
                        }
                        throw new Error(data.message || 'Unable to send your enquiry right now.');
                    }

                    form.reset();
                    window.RestaurantFormSubmit.complete(form, demo ? 'Demo requested' : 'Enquiry sent');
                    if (demo) close();
                    showNotice(false, data.message || 'Thanks! We’ll contact you shortly.', demo);
                } catch (error) {
                    window.RestaurantFormSubmit.fail(form);
                    showNotice(true, error.message || 'Unable to send your enquiry right now.', demo);
                }
            });
        });
    })();
</script>
