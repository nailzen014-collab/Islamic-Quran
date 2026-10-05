const REDUCED = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

const easeOutExpo = (t) => (t === 1 ? 1 : 1 - 2 ** (-10 * t));

/**
 * Numbers that count up once they scroll into view.
 */
export function initCounters({ prefersReducedMotion = REDUCED, scope = document }) {
    const counters = scope.querySelectorAll('[data-count-to]:not([data-count-bound])');

    const render = (el, value) => {
        const decimals = Number(el.dataset.decimals || 0);

        el.textContent =
            value.toLocaleString('id-ID', { minimumFractionDigits: decimals, maximumFractionDigits: decimals }) +
            (el.dataset.suffix || '');
    };

    const run = (el) => {
        const target = Number(el.dataset.countTo || 0);

        if (prefersReducedMotion) {
            render(el, target);
            return;
        }

        const duration = 1400;
        const start = performance.now();

        const frame = (now) => {
            const progress = Math.min((now - start) / duration, 1);
            render(el, target * easeOutExpo(progress));

            if (progress < 1) requestAnimationFrame(frame);
        };

        requestAnimationFrame(frame);
    };

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;
                run(entry.target);
                observer.unobserve(entry.target);
            });
        },
        { threshold: 0.35 },
    );

    counters.forEach((el) => {
        el.dataset.countBound = 'true';
        render(el, 0);
        observer.observe(el);
    });
}