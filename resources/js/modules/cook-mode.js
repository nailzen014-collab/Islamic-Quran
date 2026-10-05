import { pushToast } from './http';

const REDUCED = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

/**
 * Guided cook mode: one step at a time, with per-step timer and progress.
 */
export function initCookMode({ gsap, prefersReducedMotion = REDUCED }) {
    const root = document.querySelector('[data-cook-mode]');

    if (!root) return;

    const steps = [...root.querySelectorAll('[data-cook-step]')];
    const total = steps.length;
    let current = 0;
    let remaining = 0;
    let timerId = null;

    const totalEl = root.querySelector('[data-cook-progress-label]');
    const bar = root.querySelector('[data-cook-progress-bar]');
    const badge = root.querySelector('[data-cook-progress-badge]');
    const timerEl = root.querySelector('[data-cook-timer]');
    const timerRing = root.querySelector('[data-cook-timer-ring]');

    const render = () => {
        const percent = total > 1 ? (current / (total - 1)) * 100 : 100;

        if (bar) bar.style.width = `${percent}%`;
        if (badge) badge.textContent = `${current + 1} / ${total}`;
        if (totalEl) totalEl.textContent = `Langkah ${current + 1} dari ${total}`;

        root.querySelectorAll('[data-cook-dot]').forEach((dot, index) => {
            dot.classList.toggle('is-done', index < current);
            dot.classList.toggle('is-active', index === current);
        });

        root.querySelectorAll('[data-cook-prev]').forEach((el) => (el.disabled = current === 0));
        root.querySelectorAll('[data-cook-next]').forEach((el) => (el.disabled = current === total - 1));

        const active = steps[current];
        active?.scrollIntoView({ behavior: prefersReducedMotion ? 'auto' : 'smooth', block: 'nearest' });

        if (prefersReducedMotion) return;

        gsap.fromTo(
            active,
            { opacity: 0, y: 18 },
            { opacity: 1, y: 0, duration: 0.55, ease: 'power3.out', clearProps: 'transform' },
        );
    };

    const format = (seconds) => {
        const m = Math.floor(seconds / 60);
        const s = seconds % 60;
        return `${m}:${String(s).padStart(2, '0')}`;
    };

    const paintTimer = () => {
        if (!timerEl) return;

        timerEl.textContent = format(remaining);

        if (timerRing) {
            const progress = remaining / (Number(timerRing.dataset.duration) || 1);
            timerRing.style.setProperty('--progress', progress.toFixed(3));
        }
    };

    const stopTimer = () => {
        if (timerId) clearInterval(timerId);
        timerId = null;
    };

    root.querySelectorAll('[data-cook-start]').forEach((button) => {
        button.addEventListener('click', () => {
            remaining = Number(button.dataset.cookStart);
            stopTimer();
            paintTimer();

            timerId = setInterval(() => {
                remaining = Math.max(remaining - 1, 0);
                paintTimer();

if (remaining === 0) {
                    stopTimer();
                    pushToast('Waktu habis! Cek masakannya sekarang.', 'info');
                }
            }, 1000);
        });
    });

    root.querySelectorAll('[data-cook-reset]').forEach((button) => {
        button.addEventListener('click', () => {
            stopTimer();
            remaining = Number(button.dataset.cookReset) || 0;
            paintTimer();
        });
    });

    root.querySelectorAll('[data-cook-next]').forEach((button) => {
        button.addEventListener('click', () => {
            if (current >= total - 1) return;
            current += 1;
            render();
        });
    });

    root.querySelectorAll('[data-cook-prev]').forEach((button) => {
        button.addEventListener('click', () => {
            if (current <= 0) return;
            current -= 1;
            render();
        });
    });

    root.querySelectorAll('[data-cook-jump]').forEach((dot) => {
        dot.addEventListener('click', () => {
            current = Number(dot.dataset.cookJump);
            render();
        });
    });

    root.addEventListener('keydown', (event) => {
        if (event.key === 'ArrowRight') {
            current = Math.min(current + 1, total - 1);
            render();
        }

        if (event.key === 'ArrowLeft') {
            current = Math.max(current - 1, 0);
            render();
        }
    });

    if (timerRing) {
        remaining = Number(timerRing.dataset.duration) || 0;
        paintTimer();
    }

    render();
}