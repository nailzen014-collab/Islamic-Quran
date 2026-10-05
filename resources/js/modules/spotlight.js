const REDUCED = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

/**
 * Cursor-following glow for cards. Disabled on touch devices.
 */
export function initSpotlight({ prefersReducedMotion = REDUCED, scope = document }) {
    const targets = scope.querySelectorAll('[data-spotlight]:not([data-spotlight-bound])');

    targets.forEach((el) => {
        el.dataset.spotlightBound = 'true';

        if (prefersReducedMotion || window.matchMedia('(hover: none)').matches) {
            el.classList.add('is-spotlit');
            return;
        }

        el.addEventListener('pointermove', (event) => {
            const rect = el.getBoundingClientRect();

            el.style.setProperty('--mx', `${event.clientX - rect.left}px`);
            el.style.setProperty('--my', `${event.clientY - rect.top}px`);
            el.classList.add('is-spotlit');
        });

        el.addEventListener('pointerleave', () => el.classList.remove('is-spotlit'));
    });
}