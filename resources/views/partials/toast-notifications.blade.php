<style>
    .koya-toast-stack {
        position: fixed;
        top: 1.25rem;
        right: 1.25rem;
        z-index: 220;
        display: flex;
        flex-direction: column;
        gap: 0.85rem;
        width: min(25rem, calc(100vw - 1.5rem));
        pointer-events: none;
    }

    .koya-toast {
        position: relative;
        overflow: hidden;
        display: grid;
        grid-template-columns: auto 1fr auto;
        align-items: start;
        gap: 0.9rem;
        padding: 1rem 1rem 1rem 0.95rem;
        border-radius: 1.15rem;
        border: 1px solid rgba(255, 255, 255, 0.16);
        color: #fff;
        box-shadow: 0 24px 54px rgba(15, 23, 42, 0.26);
        backdrop-filter: blur(18px);
        -webkit-backdrop-filter: blur(18px);
        pointer-events: auto;
        animation: koyaToastIn 0.28s cubic-bezier(0.22, 1, 0.36, 1) forwards;
    }

    .koya-toast[data-state="closing"] {
        animation: koyaToastOut 0.22s ease forwards;
    }

    .koya-toast-success {
        background: linear-gradient(135deg, rgba(6, 95, 70, 0.96), rgba(16, 185, 129, 0.86));
    }

    .koya-toast-error {
        background: linear-gradient(135deg, rgba(127, 29, 29, 0.97), rgba(220, 38, 38, 0.88));
    }

    .koya-toast-info {
        background: linear-gradient(135deg, rgba(30, 64, 175, 0.96), rgba(59, 130, 246, 0.88));
    }

    .koya-toast-icon {
        width: 2.2rem;
        height: 2.2rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.16);
        flex-shrink: 0;
    }

    .koya-toast-body {
        min-width: 0;
    }

    .koya-toast-title {
        display: block;
        margin-bottom: 0.2rem;
        font-size: 0.72rem;
        font-weight: 900;
        letter-spacing: 0.16em;
        text-transform: uppercase;
        opacity: 0.78;
    }

    .koya-toast-text {
        display: block;
        font-size: 0.9rem;
        font-weight: 700;
        line-height: 1.45;
        word-break: break-word;
    }

    .koya-toast-close {
        width: 2rem;
        height: 2rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        border: 0;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.12);
        color: inherit;
        cursor: pointer;
        transition: background 0.2s ease, transform 0.2s ease;
    }

    .koya-toast-close:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: scale(1.04);
    }

    .koya-toast-progress {
        position: absolute;
        left: 0;
        right: 0;
        bottom: 0;
        height: 3px;
        background: rgba(255, 255, 255, 0.22);
        transform-origin: left;
        animation: koyaToastProgress linear forwards;
    }

    @keyframes koyaToastIn {
        from {
            opacity: 0;
            transform: translate3d(0, -12px, 0) scale(0.98);
        }
        to {
            opacity: 1;
            transform: translate3d(0, 0, 0) scale(1);
        }
    }

    @keyframes koyaToastOut {
        from {
            opacity: 1;
            transform: translate3d(0, 0, 0) scale(1);
        }
        to {
            opacity: 0;
            transform: translate3d(0, -8px, 0) scale(0.98);
        }
    }

    @keyframes koyaToastProgress {
        from { transform: scaleX(1); }
        to { transform: scaleX(0); }
    }

    @media (max-width: 640px) {
        .koya-toast-stack {
            top: auto;
            right: 0.75rem;
            bottom: 0.9rem;
            width: calc(100vw - 1.5rem);
        }

        .koya-toast {
            grid-template-columns: auto 1fr;
            gap: 0.75rem;
            padding-right: 0.9rem;
        }

        .koya-toast-close {
            position: absolute;
            top: 0.65rem;
            right: 0.65rem;
        }
    }
</style>

<script>
    (() => {
        if (window.KoyaNotifications) {
            return;
        }

        const icons = {
            success: '<svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',
            error: '<svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 8v4m0 4h.01M10.29 3.86l-7.4 12.82A2 2 0 004.62 20h14.76a2 2 0 001.73-3.32l-7.4-12.82a2 2 0 00-3.46 0z" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
            info: '<svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 8h.01M11 12h1v4h1m-1 5a9 9 0 110-18 9 9 0 010 18z" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
        };

        const titles = {
            success: 'Success',
            error: 'Error',
            info: 'Notice',
        };

        const getStack = () => {
            let stack = document.querySelector('.koya-toast-stack');

            if (!stack) {
                stack = document.createElement('div');
                stack.className = 'koya-toast-stack';
                document.body.appendChild(stack);
            }

            return stack;
        };

        const removeToast = (toast) => {
            if (!(toast instanceof HTMLElement) || toast.dataset.state === 'closing') {
                return;
            }

            toast.dataset.state = 'closing';
            window.setTimeout(() => toast.remove(), 220);
        };

        const show = (message, level = 'info', options = {}) => {
            if (!message) {
                return;
            }

            const normalizedLevel = ['success', 'error', 'info'].includes(level) ? level : 'info';
            const duration = Number(options.duration) > 0 ? Number(options.duration) : 4200;
            const toast = document.createElement('div');
            toast.className = `koya-toast koya-toast-${normalizedLevel}`;
            toast.innerHTML = `
                <span class="koya-toast-icon">${icons[normalizedLevel]}</span>
                <div class="koya-toast-body">
                    <span class="koya-toast-title">${titles[normalizedLevel]}</span>
                    <span class="koya-toast-text"></span>
                </div>
                <button type="button" class="koya-toast-close" aria-label="Dismiss notification">
                    <svg viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="M6 6l8 8M14 6l-8 8" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                </button>
                <span class="koya-toast-progress"></span>
            `;

            toast.querySelector('.koya-toast-text').textContent = message;

            const progress = toast.querySelector('.koya-toast-progress');
            if (progress instanceof HTMLElement) {
                progress.style.animationDuration = `${duration}ms`;
            }

            toast.querySelector('.koya-toast-close')?.addEventListener('click', () => removeToast(toast));
            getStack().appendChild(toast);

            window.setTimeout(() => removeToast(toast), duration);
        };

        const consumeFeedback = (root = document) => {
            root.querySelectorAll('[data-async-feedback], [data-page-feedback]').forEach((feedback) => {
                if (!(feedback instanceof HTMLElement) || feedback.dataset.feedbackConsumed === 'true') {
                    return;
                }

                const message = feedback.textContent?.trim();
                if (!message) {
                    feedback.dataset.feedbackConsumed = 'true';
                    return;
                }

                feedback.dataset.feedbackConsumed = 'true';
                show(
                    message,
                    feedback.dataset.feedbackLevel || 'info',
                    { duration: feedback.dataset.feedbackDuration }
                );
            });
        };

        window.KoyaNotifications = {
            show,
            consumeFeedback,
        };

        document.addEventListener('DOMContentLoaded', () => consumeFeedback(document));
    })();
</script>
